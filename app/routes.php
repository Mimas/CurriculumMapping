<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::when('admin*', 'admin');
Route::filter('admin', function() {
  if (!Bentleysoft\Helper::userHasAccess(array('application.admin'))) {
    return \Illuminate\Support\Facades\Redirect::to('/login');
  }
  return;
});

Route::controller('admin', 'AdminController');

Route::when('redis*', 'redis');
Route::filter('redis', function()
{
    if (! Bentleysoft\Helper::userHasAccess(array('redis.admin'))) {
        // \Illuminate\Support\Facades\Redirect::to('login');
        return \Illuminate\Support\Facades\Redirect::to('/login');
    }
  return;
});
 
/**
 * Redis stuff
 */
Route::controller('redis', 'RedisController');

/**
 * Resources
 * browse
 * pass int pageSize
 */
Route::get('/koko', function() {
  // get/set pageSize
  $pageSize = Input::get('pageSize', 25);
  
  // our query string
  $query = '';

  // current page (-1)
  $page = Input::get('page',1)-1;

  // calculate offset
  $offset = $page*$pageSize;

  // get the data
  $data = Bentleysoft\ES\Service::test($offset, $pageSize, $query, Input::get('audience','FE'));

  var_dump($data);

});


/**
 * Resources
 * browse
 * pass int pageSize
 */
Route::get('/resources', function() {
  // get/set pageSize
  $pageSize = Input::get('pageSize', 25);
  
  // our query string
  $query = Input::get('q', '*');

  // current page (-1)
  $page = Input::get('page',1)-1;

  // calculate offset
  $offset = $page*$pageSize;

  // get the data
  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query, Input::get('audience','FE'));

  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], $pageSize);

  // add any query string..
  if ($query<>'*') {
    $resources->addQuery('q', $query);
  }

  // custom pagination
  if ($pageSize<>25) {
    $resources->addQuery('pageSize', $pageSize);
  }
  $presenter = new Illuminate\Pagination\BootstrapPresenter($resources);

  // get subjectAreas
  $subjectAreas = Subjectarea::where('activated','=',1)
                              ->get();


  return View::make('resources')->with(array('data'=>$data,
                                             'resources'=>$resources,
                                             'subjectAreas'=>$subjectAreas,
                                             'presenter'=>$presenter,
                                             'pageSize'=>$pageSize,
                                             'total'=>$data['hits']['total'],
                                             'page'=>$page+1,
                                             ));

});

/**
 * Resources
 */
Route::get('/mapped', function() {
  $pageSize = 20;
  $query = Input::get('q', '*');

  $page = Input::get('page',1)-1;

  $offset = $page*$pageSize;

  $data = Bentleysoft\ES\Service::mapped($offset, $pageSize, $query);

  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], 20);

  // add any query string..
  if ($query<>'*') {
    $resources->addQuery('q', $query);
  }

  $presenter = new Illuminate\Pagination\BootstrapPresenter($resources);

  return View::make('resources')->with(array('data'=>$data, 'resources'=>$resources, 'presenter'=>$presenter));

});


/**
 * The Dashboard
 * TODO: Think what kind of Dashboard diferrent people get - or is it the same for all?
 */
Route::get('/', function()
{
  $pageSize = 20;

  $query = Input::get('q', '*');
  $page = Input::get('page',1)-1;

  $offset = $page*$pageSize;

  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query, \Illuminate\Support\Facades\Input::get('audience','FE'));

  //  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], 20);

  $mapped = Mapping::where('id','>', 0)->get()->count();

  return \Illuminate\Support\Facades\View::make('dashboard')->with(array('total'=>$data['hits']['total'], 'mapped'=>$mapped ) );
});

/**
 * Testbench method to add an attribute (is this the terminology?)
 * to the document - the famous edited flag
 */
Route::get('/test', function() {
 // $resource = \Bentleysoft\ES\Service::get('jorum-10949/8919');

  // $body = \Es::getSource()
  $params = array(
    'id'=>'jorum-10949/8914',
    'type'=>'learning resource',
    'index'=>'ciim',
    'body'=>array('doc'=>array('edited'=>'yes',
                                'admin'=>array('processed'=>time()*1000),
                               )
    ),

  );
  $response = \Es::update($params);
  var_export($response);

});

Route::get('/getuser', function() {
  var_dump(Sentry::getUser());
});


/**
 * TAG HT as 'FE'
 * and other tasks (meta)
 */
Route::get('/tag', function() {
  // $resource = \Bentleysoft\ES\Service::get('jorum-10949/8919');

  $from = 0;
  $pagesize = 100;

  $lds = array();
  /// header('Content-Type: text/csv');
  while (true) {
      $records = \Bentleysoft\ES\Service::orphans($from, $pagesize);

      foreach ($records['hits']['hits'] as $document) {

       //  if (isset($document['_source']['audience'] ) && $document['_source']['audience'] =='FE' ) {
       if (true || strpos($document['_id'],'ht')!=false) {
          echo "{$document['_id']}";
          echo " | ";
          echo "{$document['_source']['summary_title']}";
          echo "<br/>";
          /*
          if (isset($document['_source']['subject']['ldcode'][0] )) {
            echo "{$document['_source']['subject']['ldcode'][0]}";
            echo "\t";
            echo "{$document['_source']['subject']['ld'][0]}";
          } else {
            echo "N/A\tN/A";

          }
          echo "\n";
          */



          /// $ldSubject = array();
          /*
          if (isset($document['_source']['collection'][0]['name_for_debug'] )) {
            $ldName = \Bentleysoft\ES\Service::getCorrectLdFromWrongJorumLd($document['_source']['collection'][0]['name_for_debug']);
            $ldCode = \Bentleysoft\ES\Service::getLdCodeFromLabel($ldName);

            $ldSubject = array(
              'ld'=>array($ldName),
              'ldcode'=>array($ldCode),
              'lddebug'=>$ldCode,
            );

          } else {
            $ldSubject = array(
              'ld'=>'Unknown',
              'ldcode'=>'U',
              'lddebug'=>'Z',
            );

          }
          */
          $params = array(
            'id'=>$document['_id'],
            'type'=>$document['_type'],
            'index'=>'ciim',
            'body'=>array('doc'=>array('audience'=>array('FE'),
              'admin'=>array('processed'=>time()*1000),
            )
            ),

          );

          try
          {
            $response = \Es::update($params);
            var_dump($response);

          } catch (Exception $e) {
            echo  "Not done {$document['_id']}<br/>";
          }


        }

      }
      $from = $from + $pagesize;

      if (count($records['hits']['hits']) < $pagesize) {
        exit;
      }
  }
});



/**
 * Popular 10 or something
 *
 * <h2>{{jsonData.title[0]}}</h2>
 * <h3>ID:{{jsonData.id}}
 * {{jsonData.jmd_jacs3_subject[0]}}</h3>
 * <p class="overflow">{{jsonData.description}}<br />
 *
 */
Route::get('/popular', function() {

  $searchParams['index'] = 'ciim';

  $searchParams['size'] = 30;
  $searchParams['from'] = 0;

  $index = \Es::search($searchParams);
  $result = array();

  foreach ($index['hits']['hits'] as $hit) {
    if (isset($hit['_source']['subject']))
      $subject = $hit['_source']['subject'][0]['value'];

    $resource = \Bentleysoft\ES\Service::get($hit['_id']);

    if ($resource['_source']['admin']['source']=='jorum') {
      $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $hit['_id']), array('expand'=>'all'), 'json', 'json');
      $bitstreams = $object->getBitstreams();
    } elseif($resource['_source']['admin']['source']=='ht') {
      $bitstreams = false;
    }

    $i = 0;
    $image = '';

    while (true) {
      if ($i >= count($bitstreams)) {
        break;
      }
      $b = $bitstreams[$i];
      if (strpos($b->getMimeType(), 'image')!==false) {
        $image = $b->getRetrieveLink();
        break;
      }
      $i++;
    }
    if ($image<>'') {
        $result[] = array(
                        'title' => array(
                              0=>$hit['_source']['summary_title']
                          ),
                        'id'=>$hit['_id'],
                        'jmd_jacs3_subject'=>array(
                              $subject
                         ),
                         'image'=>"http://dspace.jorum.ac.uk/rest$image",
        );
    } else {
      $result[] = array(
        'title' => array(
          0=>$hit['_source']['summary_title']
        ),
        'id'=>$hit['_id'],
        'jmd_jacs3_subject'=>array(
          $subject
        )
      );

    }

  }
  header('Content-Type: application/json');
  echo json_encode($result);
  return;
});

// /view/jorum-10949/8894
/**
 * Route for viewing a resource
 */
Route::get('/view/{u?}/{id?}', function($u = '', $id='')
{
  $uid = "$u/$id";

  $resource = \Bentleysoft\ES\Service::get($uid);
  
  if (!$resource) {
    App::abort(404);
  }

  if ($resource['_source']['admin']['source']=='jorum') {
    $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $uid), array('expand'=>'all'), 'json', 'json');
    $bitstreams = $object->getBitstreams();
  } elseif($resource['_source']['admin']['source']=='ht') {
    $bitstreams = false;
  }
  $status = array();

  return View::make('view')->with( array('data'=>$resource, 'status'=>$status, 'bitstreams'=>$bitstreams));
});

/**
 * Content retrieve (i.e. download) handler
 */
Route::post('download', function() {
  $link = Input::get('link');
  $mime = Input::get('mime');
  $name = Input::get('filename');

  $url = 'http://dspace.jorum.ac.uk/rest'.$link;

  $stream = MIMAS\Service\Jorum\JorumApi::apiCall($url);

  $response = \Response::make($stream);
  $response->header('Content-Type', $mime);
  $response->header('Content-Disposition', 'attachment; filename="'.$name.'"');
  return $response;

});

Route::get('/edit/{u?}/{id?}', function($u = '', $id = '')
{
  $uid = "$u/$id";

  $resource = \Bentleysoft\ES\Service::get($uid);
  
  if (!$resource) {
    App::abort(404);
  }

  $result = Mapping::where('uid','=', $uid)->get();
  if ($result && count($result)>0) {
  	$meta = $result[0];
  } else {
    $meta = new Mapping;
    $meta->uid = $uid;
  }
  $status = array();
  return View::make('edit')->with( array('data'=>$meta, 'status'=>$status, 'resource'=>$resource));
});

Route::post('/edit/{uuid?}', function($uuid = '')
{
    $uid = Input::get('uid');
    $result = Mapping::where('uid','=', $uid)->get();

    if (! ($result && count($result)>0) ) {
        $meta = new Mapping;
        $meta->uid = $uid;
    } else {
        $meta = $result[0];
    }
   // TODO: validate

    $meta->subject_area = Input::get('subject_area');
    $meta->level = Input::get('level');
    $meta->content_usage = Input::get('content_usage');

    if ($meta->save()) {

      $params = array(
        'id'=>$meta->uid,
        'type'=>'learning resource',
        'index'=>'ciim',
        'body'=>array('doc'=>array('edited'=>'yes',
          'admin'=>array('processed'=>time()*1000),
        )
        ),

      );
      $response = \Es::update($params);
      $status = array('close'=>true,);

    } else {
        // TODO: Error handing        
        $status = array();   // add messages, handling etc..
    }
    return View::make('edit')->with( array('data'=>$meta, 'status'=>$status));
});



/**
* Qualifications
*/
Route::get('/qualifications', function()
{

    $q = Input::get('q','');

    $selectedQualifications = Input::get('selectedqualifications', array(1));

    $pageSize = Input::get('pageSize', 10);

    // $maxDepth = DB::table('subjectareas_view')->max('depth');

    $qualifiers = Qualifier::where('id', '>', 0)->get();



    $qualifications = QualificationView::where('qualification', 'LIKE', "%$q%")
                                        ->whereIn('qualifier_id', $selectedQualifications)
                                        ->orderBy('qualifier_id')
                                        ->orderBy('level')
                                        ->orderBy('qualification')
                                        ->paginate($pageSize);

    $paginator = Paginator::make($qualifications->getItems(), $qualifications->getTotal(), $pageSize);

    if ($q<>'') {
      $paginator->addQuery('q', $q);
    }

    if ('pageSize'<>10) {
      $paginator->addQuery('pageSize', $pageSize);

    }

    $paginator->addQuery('selectedqualifications', $selectedQualifications);

    return View::make('qualifications')->with( array('data'=>$qualifications,
                                                    'qualifiers'=>$qualifiers,
                                                    'total'=>$qualifications->getTotal(),
                                                    'page'=>$paginator->getCurrentPage(),
                                                    'paginator'=>$paginator,
                                                    'selectedQualifications'=>$selectedQualifications,
                                                    'pageSize'=>$pageSize,
                                                   ));
});

/**
 * delete hook
 */
Route::delete('/qualification/{id?}', function($id)
{
  // $q = Input::get('q','');
  $qualification = Qualification::find($id);
  $qualification->delete();

  return Redirect::to('qualifications');

});

/**************************************************** Subject areas *************************************************/

Route::post('/subject/{id?}', function($id = '')
{
  $id = Input::get('id', -1);
  $subject = Subjectarea::find($id);

  // TODO: validate

  // find record
  if (! ($subject ) ) {
    $subject = new Subjectarea;
  }
  $subject->subject = Input::get('subject');
  $subject->stuff = Input::get('stuff');

  // try save
  if ($subject->save()) {
    $status = array('close'=>true,);
  } else {
    // TODO: Error handing
    $status = array();
  }
  return View::make('subject')->with( array('data'=>$subject, 'status'=>$status));
});

Route::get('/subject/{id?}', function($id = '')
{
  $subject = Subjectarea::find($id);
  if (!$subject) {
    $subject = new Subjectarea;
  }
  return View::make('subject')->with( array('data'=>$subject, 'status'=>array()));
});

/**
 * Handle toggle ON/OFF PUT requests for Subjects....
 */
Route::put('/subject/toggle/{id?}', function($id='') {
  try {
    $id = intVal($id);

    $subject = Subjectarea::find($id);

    $subject->activated = !$subject->activated;

    if ($subject->save()) {
      return Redirect::to('subjectareas');
    } else {
      throw new Exception('Activated flag for user could not be updated...');
    }
  } catch (Exception $e) {
    throw new Exception('Activated flag for user could not be updated.');
  }
  return Redirect::to('subjectareas');
});

/*** Subject areas */
Route::get('/subjectareas', function()
{    
    $q = Input::get('q','');

    $selectedLevels = Input::get('levels', array(1,2));
    $pageSize = Input::get('pageSize', 10);

    $subjectAreas = SubjectareaView::where('subject', 'LIKE', "%$q%")
                                    ->orderBy('subject')
                                    ->paginate($pageSize);

    $paginator = Paginator::make($subjectAreas->getItems(), $subjectAreas->getTotal(), $pageSize);

    if ($q<>'') {
      $paginator->addQuery('q', $q);
    }

    if ('pageSize'<>10) {
      $paginator->addQuery('pageSize', $pageSize);
    }

    $paginator->addQuery('levels', $selectedLevels);

    return View::make('subjectareas')->with( array('data'=>$subjectAreas,
                                                   'pageSize'=>$pageSize,
                                                   'total'=>$subjectAreas->getTotal(),
                                                   'page'=>$paginator->getCurrentPage(),
                                                   'paginator'=>$paginator));
});



Route::delete('/subject/{id?}', function($id)
{
  // $q = Input::get('q','');
  $subject = Subjectarea::find($id);
  $subject->delete();

  return Redirect::to('subjectareas');

});
/**************************************************** /Subject areas **************************************************/

/******************************************************* LDCS  ********************************************************/

/*** LD Subject areas */
Route::get('/ldcs', function()
{
  $q = Input::get('q','');

  $selectedLevels = Input::get('levels', array(1,2));
  $pageSize = Input::get('pageSize', 10);

  $maxDepth = DB::table('ldcs_view')->max('depth');

  $subjects = LdcsView::where('ldsc_desc', 'LIKE', "%$q%")
    ->whereIn('depth', $selectedLevels)
    ->orderBy('ldsc_code')
    ->paginate($pageSize);


  $paginator = Paginator::make($subjects->getItems(), $subjects->getTotal(), $pageSize);

  if ($q<>'') {
    $paginator->addQuery('q', $q);
  }

  if ('pageSize'<>10) {
    $paginator->addQuery('pageSize', $pageSize);

  }

  $paginator->addQuery('levels', $selectedLevels);

  return View::make('ldcs')->with( array('data'=>$subjects,
    'maxDepth'=>$maxDepth,
    'pageSize'=>$pageSize,
    'total'=>$subjects->getTotal(),
    'page'=>$paginator->getCurrentPage(),
    'selectedLevels'=>$selectedLevels,
    'paginator'=>$paginator));
});


/**
 * Delete LDC (shouldn't really be able..._
 */
Route::delete('/ldc/{id?}', function($id)
{
  // $q = Input::get('q','');
  $ldcs = Ldcs::find($id);
  $ldcs->delete();

  return Redirect::to('ldcs');

});

Route::post('/ldc/{id?}', function($id = '')
{
  $id = Input::get('id', -1);
  $ldcs = Ldcs::find($id);

  // TODO: validate

  // find record
  if (! ($ldcs ) ) {
    $ldcs = new Ldcs();
  }
  $ldcs->ldsc_desc = Input::get('ldcs_desc');
  $ldcs->ldsc_code = Input::get('ldcs_code');

  // try save
  if ($ldcs->save()) {
    $status = array('close'=>true,);
  } else {
    // TODO: Error handing
    $status = array();
  }
  return View::make('ldc')->with( array('data'=>$ldcs, 'status'=>$status));
});

Route::get('/ldc/{id?}', function($id = '')
{
  $subject = Ldcs::find($id);
  if (!$subject) {
    $subject = new Ldcs();
  }
  return View::make('ldc')->with( array('data'=>$subject, 'status'=>array()));
});
/************************************************** /LDCS **************************************************/


Route::any('/logout', 'LoginController@actionLogout');
Route::any('/login/reset', 'LoginController@actionReset');
Route::any('/login/change', 'LoginController@actionChange');

Route::controller('login', 'LoginController');
