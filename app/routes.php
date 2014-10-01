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
Route::controller('redis', 'RedisController');

Route::get('/resources', function() {
  $pageSize = 20;
  $query = Input::get('q', '*');

  $page = Input::get('page',1)-1;

  $offset = $page*$pageSize;

  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query);

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

  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query);

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
    'id'=>'jorum-10949/8894',
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


/**
 * Popular 10 or something
 *
 *
 * <h2>{{jsonData.title[0]}}</h2>
 * <h3>ID:{{jsonData.id}}
 * {{jsonData.jmd_jacs3_subject[0]}}</h3>
 * <p class="overflow">{{jsonData.description}}<br />
 *
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
      if ($i>=count($bitstreams)) {
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
  // $meta = Mapping::where('uuid','=', '$uuid')->get();
  $result = Mapping::where('uid','=', $uid)->get();
  if ($result && count($result)>0) {
  	$meta = $result[0];
  } else {
    $meta = new Mapping;
    $meta->uid = $uid;
  }
  $status = array();
  return View::make('edit')->with( array('data'=>$meta, 'status'=>$status));
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

/*
here
*/

Route::post('/subject/{id?}', function($id = '')
{
    $id = Input::get('id', -1);
    $subject = Subjectarea::find($id);

    // TODO: validate

    // find record
    if (! ($subject ) ) {
        $subject = new Subjectarea;
    }
    $subject->area = Input::get('area');
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


Route::when('/*', 'access');
Route::filter('access', function() {
  if (! Bentleysoft\Helper::userHasAccess(array('resource.manage'))) {
    return \Illuminate\Support\Facades\Redirect::to('/login');
  }
  return;
});

Route::get('/subjectareas', function()
{
    $q = Input::get('q','');
    $subjectAreas = Subjectarea::where('area', 'LIKE', "%$q%")->paginate(10);
    return View::make('subjectareas')->with( array('data'=>$subjectAreas));
});

Route::delete('/subject/{id?}', function($id)
{
  // $q = Input::get('q','');
  $subject = Subjectarea::find($id);
  $subject->delete();

  return Redirect::to('subjectareas');

});


Route::any('/logout', 'LoginController@actionLogout');
Route::any('/login/reset', 'LoginController@actionReset');
Route::any('/login/change', 'LoginController@actionChange');

Route::controller('login', 'LoginController');
