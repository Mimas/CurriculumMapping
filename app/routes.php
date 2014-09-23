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

Route::get('/', function()
{
    $query = Input::get('q', '*');
    $data = Bentleysoft\ES\Service::browse(0, 20, $query);
    return View::make('main')->with( array('data'=>$data));
});

Route::post('/edit/{uuid?}', function($uuid = '')
{
    $uuid = Input::get('uuid');
    $result = Mapping::where('uuid','=', $uuid)->get();
    if (! ($result && count($result)>0) ) {
        $meta = new Mapping;
        $meta->uuid = $uuid;
    } else {
        $meta = $result[0];
    }

    $meta->subject_area = Input::get('subject_area');
    $meta->level = Input::get('level');
    $meta->content_usage = Input::get('content_usage');

    if ($meta->save()) {
        $status = array('close'=>true,);

    } else {
        $status = array();   // add messages, handling etc..
    }
    return View::make('edit')->with( array('data'=>$meta, 'status'=>$status));
});



Route::get('/edit/{uuid?}', function($uuid = '')
{
   // $meta = Mapping::where('uuid','=', '$uuid')->get();
    $result = Mapping::where('uuid','=', $uuid)->get();
    if ($result && count($result)>0) {
    	$meta = $result[0];
    } else {
    	$meta = new Mapping;
        $meta->uuid = $uuid;
    }
    $status = array();
   	return View::make('edit')->with( array('data'=>$meta, 'status'=>$status));
});

Route::when('/admin*', 'admin');
Route::filter('admin', function()
{
  if (! Bentleysoft\Helper::userHasAccess(array('site.admin'))) {
    return \Illuminate\Support\Facades\Redirect::to('/login');
  }
});
Route::controller('admin', 'AdminController');


Route::when('/*', 'access');
Route::filter('access', function()
{
    if (! Bentleysoft\Helper::userHasAccess(array('resource.manage'))) {
        return \Illuminate\Support\Facades\Redirect::to('/login');
    }
});


Route::any('/logout', 'LoginController@actionLogout');
Route::any('/login/reset', 'LoginController@actionReset');
Route::any('/login/change', 'LoginController@actionChange');

Route::controller('login', 'LoginController');


