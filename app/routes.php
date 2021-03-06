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
Route::filter('admin', function () {
  if (!Bentleysoft\Helper::userHasAccess(array('application.admin'))) {
    return \Illuminate\Support\Facades\Redirect::to('/login');
  }
  return;
});

Route::controller('admin', 'AdminController');

Route::when('redis*', 'redis');
Route::filter('redis', function () {
  if (!Bentleysoft\Helper::userHasAccess(array('redis.admin'))) {
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
Route::get('/koko', function () {

  $x = \Sentry::getUser();
  var_dump($x);

  return;

  // get/set pageSize
  $pageSize = Input::get('pageSize', 25);

  // our query string
  $query = '';

  // current page (-1)
  $page = Input::get('page', 1) - 1;

  // calculate offset
  $offset = $page * $pageSize;

  // get the data
  $data = Bentleysoft\ES\Service::test($offset, $pageSize, $query, Input::get('audience', 'FE'));

  var_dump($data);

});

Route::get('contact', function() {
  $user = \Sentry::getUser();
  if (!$user) {
    App::abort(401, 'You are not authorized.');
  }

  return View::make('extras.contact')->with(array('user'=>$user));
});

/**
 * Special POST case for contact form with re-captcha
 */
Route::post("contact", function()
{
  $rules =  array( 'name' => array('required'), 'email' => array('required'), 'surname' => array('required'), 'message' => array('required') );

  $validator = Validator::make(Input::all(), $rules, array('required' => 'The :attribute is required.') );

  if( $validator->fails() ) {
    $messages = $validator->messages();
    return Redirect::back()->withInput()->withErrors($messages);
  } else {
    $data = array_merge( Input::all(), array('timestamp'=>date('Y-m-d H:i:s' ), 'remote_addr'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>$_SERVER['HTTP_USER_AGENT'] ));

    Mail::send('emails.enquiry', array('data'=>$data), function($message)
    {
      $message->to('petros.diveris@manchester.ac.uk', 'Petros Diveris')
        ->cc('mark.power@manchester.ac.uk')
        ->cc('joy.hooper@jisc.ac.uk');

      $message->subject('Curriculum Mapping Tool Contact Form - '.Input::get('name') .' ' .Input::get('surname') );
      $message->from(Input::get('email'), 'Curriculum Mapping Tool web enquiry form');

    });
    return Redirect::back()->withInput()->with(array('success'=>'Your message has been received and and will be answered shortly.'));
  }
});

Route::get('session', function() {
  echo 'session haha';

  // Session::put('ala.kaka',10);
  // Session::put('ala.zakoula',10);

  var_dump(Session::all());

});

/**
 * Resources
 * browse
 * pass int pageSize
 *
 */
Route::get('/resources', function () {
  // get/set pageSize
  $pageSize = Input::get('pageSize', 25);

  // our query string
  $query = Input::get('q', '*');
  if ($query == '') {
    $query = '*';
  }

  // current page (-1)
  $page = Input::get('page', 1) - 1;

  // calculate offset
  $offset = $page * $pageSize;

  // get subjectAreas
  $subjectAreas = Subjectarea::where('activated', '=', 1)
    ->get();

  $selectedAreas = Input::get('areas', array());

  /**
   * Set the user's initial checkboxes to whatever they are set as in the User panel
   * but only if the user is not an app admin (i.e. "superuser")
   */
  if (empty($selectedAreas) && !\Bentleysoft\Helper::userHasAccess(array('application.admin'))) {
    if (Session::get('requests.resources',0) <= 1) {
      $selectedAreas = \Bentleysoft\Helper::getUserSubjectAreas();
    }
  }

  $showMapped = Input::get('show_mapped', '') <> '';
  $showUnmapped = Input::get('show_unmapped', '') <> '';
  $showViewable = Input::get('show_viewable', '') <> '';
  $showUnviewable = Input::get('show_unviewable', '') <> '';

  // get the data
  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query,
    Input::get('audience', 'FE'), $selectedAreas, $showMapped, $showUnmapped, $showViewable, $showUnviewable
  );

  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], $pageSize);

  // add any query string..
  if ($query <> '*') {
    $resources->addQuery('q', $query);
  }

  // custom pagination
  if ($pageSize <> 25) {
    $resources->addQuery('pageSize', $pageSize);
  }
  $presenter = new Illuminate\Pagination\BootstrapPresenter($resources);


  return View::make('resources')->with(array('data' => $data,
    'resources' => $resources,
    'presenter' => $presenter,
    'subjectAreas' => $subjectAreas,
    'selectedAreas' => $selectedAreas,
    'showMapped' => $showMapped,
    'showUnmapped' => $showUnmapped,
    'showViewable' => $showViewable,
    'showUnviewable' => $showUnviewable,
    'pageSize' => $pageSize,
    'total' => $data['hits']['total'],
    'page' => $page + 1,
  ));

});

/**
 * Resources
 */
Route::get('/mapped', function () {
  $pageSize = 20;
  $query = Input::get('q', '*');

  $page = Input::get('page', 1) - 1;

  $offset = $page * $pageSize;

  $data = Bentleysoft\ES\Service::mapped($offset, $pageSize, $query);

  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], 20);

  // add any query string..
  if ($query <> '*') {
    $resources->addQuery('q', $query);
  }

  // get subjectAreas
  $subjectAreas = Subjectarea::where('activated', '=', 1)
    ->get();


  $selectedAreas = Input::get('areas', array());


  /**
   * Set the user's initial checkboxes to whatever they are set as in the User panel
   * but only if the user is not an app admin (i.e. "superuser")
   */
  if (empty($selectedAreas) && !\Bentleysoft\Helper::userHasAccess(array('application.admin'))) {
    $selectedAreas = \Bentleysoft\Helper::getUserSubjectAreas();
  } else {

  }

  $presenter = new Illuminate\Pagination\BootstrapPresenter($resources);

  return View::make('resources')->with(array('data' => $data,
    'resources' => $resources,
    'presenter' => $presenter,
    'subjectAreas' => $subjectAreas,
    'selectedAreas' => $selectedAreas,
    'pageSize' => $pageSize,
    'total' => $data['hits']['total'],
    'page' => $page + 1,
  ));

});


/**
 * Get Mapping and render the edit form
 */
Route::get('/edit/{u?}/{id?}', function ($u = '', $id = '') {

  $uid = "$u";   // an id, including a jorum/one

  $mid = str_replace('10949-', '10949/', $uid);

  $resource = \Bentleysoft\ES\Service::get($uid);


  if (!$resource)  { // nothing found, abort with a 404
    App::abort(404);
  }

  $result = Mapping::where('uid', '=', $uid)
                    ->orWhere('uid', '=', $mid)
                    ->get();

  if ($result && count($result) > 0) {

    $meta = $result[0];   // Eloquent's where returns an array, we should only have one anyway

    // Get attached qualifications, if any
    $resourceQualifications = Bentleysoft\Helper::fieldList($meta->qualifications()->get()->all(), 'id' );

    // Get the 'tags' (that is the LDs) associated with the Mapping
    $resourceTags = Bentleysoft\Helper::fieldList($meta->tags()->get()->all(),'ldcs_desc');

    // get user tags
    $resourceUserTags = Bentleysoft\Helper::fieldList($meta->userTags()->get()->all(), 'label');

  } else {
    $resourceQualifications = array();
    $resourceTags = array();
    $resourceUserTags = array();

    $meta = new Mapping;
    $meta->currency = 1;
    $meta->uid = $uid;
  }

  $trackingId = Mapping::startTracking($uid);

  $qualifications = QualificationView::where('activated', '=', 1)
                                      ->orderBy('level')
                                      ->get();

  $topLevel = (isset($resource['_source']['subject'][0]['id'])) ? $resource['_source']['subject'][0]['id'] : '?';


  if ($topLevel == '?' && isset($resource['_source']['subject'][0]['ldcode'])) {
    $topLevel = $resource['_source']['subject'][0]['ldcode'][0];
  }

  $ldcsSubjects = LdcsView::where('ldcs_code', 'like', "$topLevel%.%") // Only 2nd level for now
    ->whereIn('depth', [2])
    ->get();

  $tags = array();
  foreach ($ldcsSubjects as $subject) {
    $tags[] = $subject->ldcs_desc;
  }

  $extraTags = Tag::where('id','>',0)->get();

  $userTags = array();
  foreach ($extraTags as $tag) {
    $userTags[] = $tag->label;
  }


  $status = array();
  return View::make('edit')->with(array('data' => $meta,
    'status' => $status,
    'qualifications' => $qualifications,
    'tags' => json_encode($tags),
    'userTags' => json_encode($userTags),
    'trackingId' => $trackingId,
    'resourceTags'=>implode(',', $resourceTags),
    'resourceUserTags'=>implode(',', $resourceUserTags),
    'resourceQualifications'=>$resourceQualifications,
    'resource' => $resource));
});

Route::any('/stoptracking/{id?}', function($id) {
  Mapping::stopTracking($id);
});

Route::any('report', function() {
  $history = History::all();

  $output='';
  foreach ($history as $row) {
    $row->title = 'Three little birds';
    $row->docs = 5;
    $output.=  implode(",",$row->toArray());
    $output .= "\n";


  }
  $headers = array(
    'Content-Type' => 'text/csv',
    'Content-Disposition' => 'attachment; filename="history.csv"',
  );


  return Response::make(rtrim($output, "\n"), 200, $headers);
});


/**
 * Post resource handler, the main handler really
 * TODO: Move stuff to helper/model and add validation
 */
Route::post('/edit/{uu?}/{id?}', function ($uu='', $id = '') {

  $uid = Input::get('uid');

  $result = Mapping::where('uid', '=', $uid)->get();

  $crc32 = crc32(json_encode($_POST));

  if (!($result && count($result) > 0)) {
    $meta = new Mapping;
    $meta->uid = $uid;
    $meta->checksum = 0;
  } else {
    $meta = $result[0];

  }
  // TODO: validate

  $status = array('close' => true,);

  if ($meta->checksum <> $crc32) {
    $meta->checksum = $crc32;

    MappingLdcs::where('mappings_id','=',$meta->id)
      ->delete();

    $meta->subject_area = Input::get('subject_area');
    $meta->level = Input::get('level');
    $meta->content_usage = Input::get('content_usage');
    $meta->desired_content = Input::get('desired_content');
    $meta->other_resources = Input::get('other_resources');

    $meta->other_qualifications = Input::get('other_qualifications');

    $meta->currency = Input::get('currency',0);

    if ($meta->save()) {
      $meta->attachQualifications(Input::all());
      $meta->attachTags(explode(',', Input::get('tags', '')));

      $meta->attachUserTags(explode(',', Input::get('user_tags', '')));

      if (!$meta->currency) {
        $meta->attachIssues(Input::get('issues'), Input::get('other_comments',''));
      }

      $params = array(
        'id' => $meta->uid,
        'type' => 'learning resource',
        'index' => 'ciim',
        'refresh' => true,
        'body' => array('doc' => array('edited' => true,
          'admin' => array('processed' => time() * 1000),
        )
        ),

      );
      $response = \Es::update($params);
      if (!$response) {

      }
    } else {
      // TODO: Error handing
      $status = array(); // add messages, handling etc..
    }
  }

  $trackingId = 69;

  return View::make('edit')->with(array('data' => $meta,
    'qualifications' => array(),
    'tags' => json_encode(array()),
    'trackingId' => $trackingId,
    'resourceTags'=>implode(',', array()),
    'resourceUserTags'=>implode(',', array()),
    'resourceIssues'=>array(),
    'resourceQualifications'=>array(),
    'status' => $status,
  ));

});

/**
 * The Dashboard
 * TODO: Think what kind of Dashboard diferrent people get - or is it the same for all?
 */
Route::get('/', function () {
  $pageSize = 20;

  $query = Input::get('q', '*');
  $page = Input::get('page', 1) - 1;

  $offset = $page * $pageSize;

  $data = Bentleysoft\ES\Service::browse($offset, $pageSize, $query, \Illuminate\Support\Facades\Input::get('audience', 'FE'), array(), true);

  //  $resources = Paginator::make($data['hits']['hits'], $data['hits']['total'], 20);

  $mappedResources = Bentleysoft\ES\Service::mapped($offset, $pageSize, $query);

  $mapped = $mappedResources['hits']['total'];

  // $mapped = Mapping::where('id', '>', 0)->get()->count();


  return \Illuminate\Support\Facades\View::make('dashboard')->with(array('total' => $data['hits']['total'], 'mapped' => $mapped));
});

/**
 * Testbench method to add an attribute (is this the terminology?)
 * to the document - the famous edited flag
 */
Route::get('/test', function () {

  // $resource = \Bentleysoft\ES\Service::get('jorum-10949/8919');

  // $body = \Es::getSource()
  $params = array(
    'id' => 'jorum-10949-1677',
    'type' => 'learning resource',
    'index' => 'ciim',
    'refresh' => true,
    'body' => array('doc' => array('edited' => true,
      'admin' => array('processed' => time() * 1000),
    )
    ),

  );
  $response = \Es::update($params);
  var_export($response);

});

Route::get('/getuser', function () {
  var_dump(Sentry::getUser());
});


/**
 * TAG HT as 'FE'
 * and other tasks (meta)
 */
Route::get('/tag', function () {
  // $resource = \Bentleysoft\ES\Service::get('jorum-10949/8919');

  $from = 0;
  $pagesize = 100;

  $lds = array();
  /// header('Content-Type: text/csv');
  while (true) {
    $records = \Bentleysoft\ES\Service::orphans($from, $pagesize);

    foreach ($records['hits']['hits'] as $document) {

      //  if (isset($document['_source']['audience'] ) && $document['_source']['audience'] =='FE' ) {
      if (true || strpos($document['_id'], 'ht') != false) {
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
          'id' => $document['_id'],
          'type' => $document['_type'],
          'index' => 'ciim',
          'refresh' => true,
          'body' => array('doc' => array('audience' => array('FE'),
            'admin' => array('processed' => time() * 1000),
          )
          ),
        );

        try {
          $response = \Es::update($params);
          var_dump($response);

        } catch (Exception $e) {
          echo "Not done {$document['_id']}<br/>";
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
Route::get('/popular', function () {

  $searchParams['index'] = 'ciim';

  $searchParams['size'] = 30;
  $searchParams['from'] = 0;

  $index = \Es::search($searchParams);
  $result = array();

  foreach ($index['hits']['hits'] as $hit) {
    if (isset($hit['_source']['subject']))
      $subject = $hit['_source']['subject'][0]['value'];

    $resource = \Bentleysoft\ES\Service::get($hit['_id']);

    if ($resource['_source']['admin']['source'] == 'jorum') {
      $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $hit['_id']), array('expand' => 'all'), 'json', 'json');
      $bitstreams = $object->getBitstreams();
    } elseif ($resource['_source']['admin']['source'] == 'ht') {
      $bitstreams = false;
    }

    $i = 0;
    $image = '';

    while (true) {
      if ($i >= count($bitstreams)) {
        break;
      }
      $b = $bitstreams[$i];
      if (strpos($b->getMimeType(), 'image') !== false) {
        $image = $b->getRetrieveLink();
        break;
      }
      $i++;
    }
    if ($image <> '') {
      $result[] = array(
        'title' => array(
          0 => $hit['_source']['summary_title']
        ),
        'id' => $hit['_id'],
        'jmd_jacs3_subject' => array(
          $subject
        ),
        'image' => "http://dspace.jorum.ac.uk/rest$image",
      );
    } else {
      $result[] = array(
        'title' => array(
          0 => $hit['_source']['summary_title']
        ),
        'id' => $hit['_id'],
        'jmd_jacs3_subject' => array(
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
Route::get('/view/{u?}/{id?}', function ($u = '', $id = '') {
  $uid = "$u/$id";

  $resource = \Bentleysoft\ES\Service::get($uid);
  if (!$resource) {
    App::abort(404);
  }

  if ($resource['_source']['admin']['source'] == 'jorum') {
    $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $uid), array('expand' => 'all'), 'json', 'json');
    $bitstreams = $object->getBitstreams();
  } elseif ($resource['_source']['admin']['source'] == 'ht') {

    $bitstream = new MIMAS\Service\Hairdressing\Bitstream();
    $bitstream->setBundleName('URL_BUNDLE');
    $bitstream->setName('http://hairdressing.ac.uk/'.str_replace('ht-', '', $resource['_id']));
    $bitstreams = array($bitstream);
  }

  $status = array();

  return View::make('view')
             ->with(array('data'=>$resource,
                          'status'=>$status,
                          'bestPreviewUrl'=>MIMAS\Helpers::mainPreviewUrl($bitstreams),
                          'bitstreams'=>$bitstreams)
             );
});

Route::get('/quickview/{u?}/{id?}', function ($u = '', $id = '') {
  $uid = "$u/$id";

  $resource = \Bentleysoft\ES\Service::get($uid);

  if (!$resource) {
    App::abort(404);
  }

  if ($resource['_source']['admin']['source'] == 'jorum') {
    $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $uid), array('expand' => 'all'), 'json', 'json');
    $bitstreams = $object->getBitstreams();
  } elseif ($resource['_source']['admin']['source'] == 'ht') {

    $bitstream = new MIMAS\Service\Hairdressing\Bitstream();
    $bitstream->setBundleName('URL_BUNDLE');
    $bitstream->setName('http://hairdressing.ac.uk/'.str_replace('ht-', '', $resource['_id']));
    $bitstreams = array($bitstream);
  }
  $status = array();

  return View::make('quickview')->with(array('data' => $resource, 'status' => $status, 'bitstreams' => $bitstreams));
});

Route::get('/quickviewjs/{u?}/{id?}', function ($u = '', $id = '') {
  $uid = "$u/$id";

  $resource = \Bentleysoft\ES\Service::get($uid);

  if (!$resource) {
    App::abort(404);
  }

  if ($resource['_source']['admin']['source'] == 'jorum') {
    $object = MIMAS\Service\Jorum\Item::find(str_replace('jorum-', '', $uid), array('expand' => 'all'), 'json', 'json');
    $allStreams = $object->getBitstreams();

    // Kill lincense and other grap. This will eventuallyhave to be moved into getBitStreams($clean =  true)
    $bitstreams = array();

    foreach($allStreams as $stream) {
      if (strpos($stream->getBundleName(), 'LICENSE')===false) {
        $stream->previewUrl = $stream->getPreviewUrl();
        $bitstreams[] = $stream;
      }
    }

  } elseif ($resource['_source']['admin']['source'] == 'ht') {

    $bitstream = new MIMAS\Service\Hairdressing\Bitstream();
    $bitstream->setBundleName('URL_BUNDLE');
    $bitstream->setName('http://hairdressing.ac.uk/'.str_replace('ht-', '', $resource['_id']));
    $bitstream->previewUrl = 'http://hairdressing.ac.uk/'.str_replace('ht-', '', $resource['_id']);

    $bitstreams = array($bitstream);

  }
  $status = array();

  return Response::json(array('data'=>$resource, 'status'=>$status, 'bitstreams'=>$bitstreams ));

});


/**
 * Content retrieve (i.e. download) handler
 */
Route::post('download', function () {
  $link = Input::get('link');
  $mime = Input::get('mime');
  $name = Input::get('filename');

  $url = 'http://dspace.jorum.ac.uk/rest' . $link;

  $stream = MIMAS\Service\Jorum\JorumApi::apiCall($url);

  $response = \Response::make($stream);
  $response->header('Content-Type', $mime);
  $response->header('Content-Disposition', 'attachment; filename="' . $name . '"');
  return $response;

});

Route::any('preview/{id?}', function($id) {
  $bitstream = MIMAS\Service\Jorum\Bitstream::find($id, array(), 'json', 'json');
  if ($bitstream->getMimeType()=='application/zip') {
    return View::make('blankview')->with(array('bitstream'=>$bitstream));
  } else {
    return View::make('preview')->with(array('bitstream'=>$bitstream));
  }
});


Route::get('remap', function() {
  $dbMapped = Mapping::get();

  foreach ($dbMapped as $key => $map) {
    echo $map->uid."<br/>";
    $params = array(
      'id' => $map->uid,
      'type' => 'learning resource',
      'index' => 'ciim',
      'refresh' => true,
      'body' => array('doc' => array('edited' => true,
        'admin' => array('processed' => time() * 1000),
      )
      ),

    );
    $x = time();

    $response = \Es::update($params);
    var_export($response);
    # code...
  }


  echo 'asa';
});

/**
 * Toggle edited on off
 */
Route::put('/resource/toggle/{u?}/{id?}', function ($u='', $id='') {

  $uid = Input::get('_id', '');
  if ($uid <> '') {
    $resource = \Bentleysoft\ES\Service::get($uid);

    if (!$resource) {
      App::abort(404);
    }
    if (isset($resource['_source']['edited']) && $resource['_source']['edited'] == 'yes' || isset($resource['_source']['edited']) && $resource['_source']['edited'] == true) {
      $edited = false;
    } else {
      $edited = true;
    }

    $params = array(
      'id' => $uid,
      'type' => 'learning resource',
      'index' => 'ciim',
      'refresh' => true,
      'body' => array('doc' => array('edited' => $edited,
        'admin' => array('processed' => time() * 1000),
      )
      ),

    );
    $x = time();

    $response = \Es::update($params);
    if (!$response) ; // code sniffing avoidance scheme
  }
  $url = Input::get('return_to', 'resources');

  return Redirect::to($url, 303)->withInput(array($uid => $edited), array('stuff' => ''));
});

/**
 * Toggle Viewable on off
 *
 */
Route::put('/resource/setviewable/{u?}/{id?}', function ($u ='', $id='') {
  $uid = Input::get('_id', '');

  if ($uid <> '') {
    $resource = \Bentleysoft\ES\Service::get($uid);

    if (!$resource) {
      App::abort(404);
    }

    if (Input::get('origin','')=='') {  // coming from browser
      if (isset($resource['_source']['viewable']) && $resource['_source']['viewable'] == 'yes' || isset($resource['_source']['viewable']) && $resource['_source']['viewable'] == true) {
        $viewable = false;
      } else {
        $viewable = true;
      }
    } else {
      $viewable = Input::get('viewable', '') <> '';
    }
    $params = array(
      'id' => $uid,
      'type' => 'learning resource',
      'index' => 'ciim',
      'refresh' => true,
      'body' => array('doc' => array('viewable' => $viewable,
        'admin' => array('processed' => time() * 1000),
      )
      ),
    );

    $x = time();

    $response = \Es::update($params);
    if (!$response) ; // code sniffing avoidance scheme
  }
  $url = Input::get('return_to', 'resources');

  return Redirect::to($url, 303)->withInput(array($uid => $viewable), array('stuff' => ''));
});


/**
 * Toggle Publish aka "Good to Go"
 */
Route::put('/resource/publish/{u?}/{id?}', function ($u, $id = '') {

  $uid = Input::get('_id', '');

  if ($uid <> '') {
    $resource = \Bentleysoft\ES\Service::get($uid);

    if (!$resource) {
      App::abort(404);
    }

    $promote = Input::get('action') == 'publish';
    $params = array(
      'id' => $uid,
      'type' => 'learning resource',
      'index' => 'ciim',
      'refresh' => true,
      'body' => array('doc' => array('fewindow' => $promote,
        'admin' => array('processed' => time() * 1000),
      )
      ),
    );

    $response = \Es::update($params);

    $data = Bentleysoft\ES\Service::browse(0, 50, '', Input::get('audience', 'FE'), array());

    if (!$response) ; // code sniffing avoidance scheme
  }
  $url = Input::get('return_to', 'resources');
  return Redirect::to($url, 303)->withInput(array($uid => $promote), array('stuff' => 'refresh'));
});



/**
 * *************************************************  Qualifications ****************************************************
 */
Route::get('/qualifications', function () {
  $q = Input::get('q', '');

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

  if ($q <> '') {
    $paginator->addQuery('q', $q);
  }

  if ('pageSize' <> 10) {
    $paginator->addQuery('pageSize', $pageSize);

  }

  $paginator->addQuery('selectedqualifications', $selectedQualifications);

  return View::make('qualifications')->with(array('data' => $qualifications,
    'qualifiers' => $qualifiers,
    'total' => $qualifications->getTotal(),
    'page' => $paginator->getCurrentPage(),
    'paginator' => $paginator,
    'selectedQualifications' => $selectedQualifications,
    'pageSize' => $pageSize,
  ));
});

/**
 * delete hook
 */
Route::delete('/qualification/{id?}', function ($id) {
  // $q = Input::get('q','');
  $qualification = Qualification::find($id);
  $qualification->delete();

  return Redirect::to('qualifications');

});

/**
 * Handle toggle ON/OFF PUT requests for Subjects....
 */
Route::put('/qualification/toggle/{id?}', function ($id = '') {
  try {
    $id = intVal($id);

    $qual = Qualification::find($id);

    $qual->activated = !$qual->activated;

    if ($qual->save()) {
      return Redirect::to(Input::get('return_to', 'qualifications'));
    } else {
      throw new Exception('Activated flag for user could not be updated...');
    }
  } catch (Exception $e) {
    throw new Exception('Activated flag for user could not be updated.');
  }
  return Redirect::to(Input::get('return_to', 'qualifications'));
});

Route::get('/qualification/{id?}', function ($id = '') {
  $qualification = Qualification::find($id);

  $qualifiers = Qualifier::where('id', '>', 0)->get();

  if (!$qualification) {
    $qualification = new Qualification();
  }
  return View::make('qualification')
    ->with(array('data' => $qualification,
      'qualifiers' => $qualifiers,
      'status' => array()));
});


/**************************************************** Subject areas *************************************************/

Route::post('/subject/{id?}', function ($id = '') {
  $id = Input::get('id', -1);
  $subject = Subjectarea::find($id);

  // TODO: validate

  // find record
  if (!($subject)) {
    $subject = new Subjectarea;
  }
  $subject->subject = Input::get('subject');
  $subject->stuff = Input::get('stuff');

  // try save
  if ($subject->save()) {
    $status = array('close' => true,);
  } else {
    // TODO: Error handing
    $status = array();
  }
  return View::make('subject')->with(array('data' => $subject, 'status' => $status));
});

Route::get('/subject/{id?}', function ($id = '') {
  $subject = Subjectarea::find($id);
  if (!$subject) {
    $subject = new Subjectarea;
  }
  return View::make('subject')->with(array('data' => $subject, 'status' => array()));
});

/**
 * Handle toggle ON/OFF PUT requests for Subjects....
 */
Route::put('/subject/toggle/{id?}', function ($id = '') {
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
Route::get('/subjectareas', function () {
  $q = Input::get('q', '');

  $selectedLevels = Input::get('levels', array(1, 2));
  $pageSize = Input::get('pageSize', 10);

  $subjectAreas = SubjectareaView::where('subject', 'LIKE', "%$q%")
    ->orderBy('subject')
    ->paginate($pageSize);

  $paginator = Paginator::make($subjectAreas->getItems(), $subjectAreas->getTotal(), $pageSize);

  if ($q <> '') {
    $paginator->addQuery('q', $q);
  }

  if ('pageSize' <> 10) {
    $paginator->addQuery('pageSize', $pageSize);
  }

  $paginator->addQuery('levels', $selectedLevels);

  return View::make('subjectareas')->with(array('data' => $subjectAreas,
    'pageSize' => $pageSize,
    'total' => $subjectAreas->getTotal(),
    'page' => $paginator->getCurrentPage(),
    'paginator' => $paginator));
});


Route::delete('/subject/{id?}', function ($id) {
  // $q = Input::get('q','');
  $subject = Subjectarea::find($id);
  $subject->delete();

  return Redirect::to('subjectareas');

});
/**************************************************** /Subject areas **************************************************/

/******************************************************* LDCS  ********************************************************/

/*** LD Subject areas */
Route::get('/ldcs', function () {
  $q = Input::get('q', '');

  $selectedLevels = Input::get('levels', array(1, 2));
  $pageSize = Input::get('pageSize', 10);

  $maxDepth = DB::table('ldcs_view')->max('depth');

  $subjects = LdcsView::where('ldcs_desc', 'LIKE', "%$q%")
    ->whereIn('depth', $selectedLevels)
    ->orderBy('ldcs_code')
    ->paginate($pageSize);


  $paginator = Paginator::make($subjects->getItems(), $subjects->getTotal(), $pageSize);

  if ($q <> '') {
    $paginator->addQuery('q', $q);
  }

  if ('pageSize' <> 10) {
    $paginator->addQuery('pageSize', $pageSize);

  }

  $paginator->addQuery('levels', $selectedLevels);

  return View::make('ldcs')->with(array('data' => $subjects,
    'maxDepth' => $maxDepth,
    'pageSize' => $pageSize,
    'total' => $subjects->getTotal(),
    'page' => $paginator->getCurrentPage(),
    'selectedLevels' => $selectedLevels,
    'paginator' => $paginator));
});


/**
 * Delete LDC (shouldn't really be able..._
 */
Route::delete('/ldc/{id?}', function ($id) {
  // $q = Input::get('q','');
  $ldcs = Ldcs::find($id);
  $ldcs->delete();

  return Redirect::to('ldcs');

});

Route::post('/ldc/{id?}', function ($id = '') {
  $id = Input::get('id', -1);
  $ldcs = Ldcs::find($id);

  // TODO: validate

  // find record
  if (!($ldcs)) {
    $ldcs = new Ldcs();
  }
  $ldcs->ldcs_desc = Input::get('ldcs_desc');
  $ldcs->ldcs_desc = Input::get('ldcs_code');

  // try save
  if ($ldcs->save()) {
    $status = array('close' => true,);
  } else {
    // TODO: Error handing
    $status = array();
  }
  return View::make('ldc')->with(array('data' => $ldcs, 'status' => $status));
});

Route::get('/ldc/{id?}', function ($id = '') {
  $subject = Ldcs::find($id);
  if (!$subject) {
    $subject = new Ldcs();
  }
  return View::make('ldc')->with(array('data' => $subject, 'status' => array()));
});
/************************************************** /LDCS **************************************************/


Route::any('/logout', 'LoginController@actionLogout');
Route::any('/login/reset', 'LoginController@actionReset');
Route::any('/login/change', 'LoginController@actionChange');

Route::controller('login', 'LoginController');



/********************************** export mapping data for importing on the otter side ;o( ***********************/

/**
 * Resources
 */
Route::get('/export', function () {
  // $resource = \Bentleysoft\ES\Service::get('jorum-10949/8919');

  $from = 0;
  $pagesize = 100;

  $lds = array();
  /// header('Content-Type: text/csv');
  while (true) {
    $records = \Bentleysoft\ES\Service::mapped($from, $pagesize);

    foreach ($records['hits']['hits'] as $document) {

      //  if (isset($document['_source']['audience'] ) && $document['_source']['audience'] =='FE' ) {
      if (true || strpos($document['_id'], '14732') != false) {

        $bridge = new Bridge;

        $bridge->uid = $document['_id'];
        $bridge->uuid = $document['_source']['admin']['uuid'];

        if (isset($document['_source']['subject']) && is_array($document['_source']['subject'])) {
          if (isset($document['_source']['subject'][0]['ld']))
            $bridge->ld = $document['_source']['subject'][0]['ld'][0];
          
          if (isset($document['_source']['subject'][0]['lddebug'])) 
            $bridge->lddebug = $document['_source']['subject'][0]['lddebug'];

          if (isset($document['_source']['subject'][0]['ldcode']))
            $bridge->ldcode = $document['_source']['subject'][0]['ldcode'][0];
        }          

        if (isset($document['_source']['edited'])) {
          $bridge->edited = $document['_source']['edited'];
        } else {
          $bridge->edited = false;
        }

        if (isset($document['_source']['fewindow'])) {
          $bridge->fewindow = $document['_source']['fewindow'];
        } else {
          $bridge->fewindow = false;
        }

        if (isset($document['_source']['viewable'])) {
          $bridge->viewable = $document['_source']['viewable'];
        }

        $bridge->save();

        echo "{$document['_id']}";
        echo " | ";
        echo "{$document['_source']['summary_title']}";
        echo "<br/>";


      }
    }
    $from = $from + $pagesize;

    if (count($records['hits']['hits']) < $pagesize) {
      exit;
    }
  }

});


Route::get('/import', function () {
  die('Have already imported. Cotact devs@window');
  $data = Bridge::get()->all();
  foreach ($data as $record) {
    echo "Updating document {$record->uid}<br/>";
    $id = str_replace('/', '-', $record->uid);
    if ($id<>'jorum-10949-19209') {
        $params = array(
          'id' => $id,
          'type' => 'learning resource',
          'index' => 'ciim',
          'refresh' => true,
          'body' => array('doc' => array('edited' => true, 'viewable' => $record->viewable, 'edited'=>$record->edited, 'fewindow'=>$record->fewindow,
            'admin' => array('processed' => time() * 1000),
            )
          ),

        );

        $response = \Es::update($params);

        if (!$response) {
          var_dump($response);
        } else {
          echo "UPDATED document {$record->uid}<br/>";

        }

      echo "<br/>";
    }
  }

});

Route::get('/importq', function () {

  $data = Mapping::get()->all();

  foreach ($data as $record) {
    echo "Updating document {$record->uid}<br/>";
    $id = str_replace('/', '-', $record->uid);

    if ($id<>'jorum-10949-19209') {

      $resourceQualifications = Bentleysoft\Helper::fieldList($record->qualifications()->orderBy('level')->get()->all(), 'level' );
      var_export($resourceQualifications);

      // Get the 'tags' (that is the LDs) associated with the Mapping
      $tmp = Bentleysoft\Helper::fieldList($record->tags()->get()->all(),'ldcs_code');
      
      $resourceTags = array();

      foreach ($tmp as $key => $value) {
        # code...
        if (strpos($value, '.')==strlen($value)-1) {
          $value = str_replace('.', '', $value);
        } else {

        }
        $resourceTags[] = $value;
      }

      var_export($resourceQualifications);

      // get user tags
      $resourceUserTags = Bentleysoft\Helper::fieldList($record->userTags()->get()->all(), 'label');
      var_export($resourceUserTags);


        $params = array(
          'id' => $id,
          'type' => 'learning resource',
          'index' => 'ciim',
          'refresh' => true,
          'body' => array('doc' => array('mapping'=>'standard', 
                                         'qualifications'=>$resourceQualifications, 
                                         'subjectareas'=>$resourceTags,
                                         'tags'=>$resourceUserTags,
                                         
            'admin' => array('processed' => time() * 1000),
            )
          ),

        );

        $response = \Es::update($params);
        echo "<br/>";
        if (!$response) {
          var_dump($response);
        } else {
          echo "UPDATED document {$record->uid}<br/>";

        }

      echo "<br/>";

    }
  }
 });


/**
 *
 * ht handler to add Subject Area to all HT resources ('H')
 * 
 */
Route::get('/ht', function () {
  $from = 0;
  $pagesize = 100;

  $lds = array();
  /// header('Content-Type: text/csv');


  $json = ' {

        "@reference": "literal",
        "id": "H",
        "name": [
          {
            "primary": true,
            "type": "preferred",
            "value": "FAMILY CARE / PERSONAL DEVELOPMENT / PERSONAL CARE and APPEARANCE"
          }
        ],
        "vocabulary": "learn direct"
    
  }
  ';
  $subject = json_decode($json);

  while (true) {
    $records = \Bentleysoft\ES\Service::orphans($from, $pagesize);

    foreach ($records['hits']['hits'] as $document) {

      //  if (isset($document['_source']['audience'] ) && $document['_source']['audience'] =='FE' ) {
      if (true || strpos($document['_id'], 'ht') != false) {
        echo "{$document['_id']}";
        echo " | ";
        echo "{$document['_source']['summary_title']}";
        echo "<br/>";

        $params = array(
          'id' => $document['_id'],
          'type' => $document['_type'],
          'index' => 'ciim',
          'refresh' => true,
          'body' => array('doc' => array('subject' => array($subject),
            'admin' => array('processed' => time() * 1000),
          )
          ),
        );

        try {
           $response = \Es::update($params);
           var_dump($response);

        } catch (Exception $e) {
          echo "Not done {$document['_id']}<br/>";
        }
      }
    }
    $from = $from + $pagesize;

    if (count($records['hits']['hits']) < $pagesize) {
      exit;
    }
  }
 });