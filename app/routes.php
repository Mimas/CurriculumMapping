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

Route::get('/edit/{uuid?}', function($uuid)
{
   // $meta = Mapping::where('uuid','=', '$uuid')->get();
    $result = Mapping::where('uuid','=', $uuid)->get();
    if ($result) {
    	$meta = $result[0];
    } else {
    	$meta = new Mapping;
    }
   	return View::make('edit')->with( array('data'=>$meta));;
});

Route::get('/pako', function()
{
    $query = Input::get('q', '*');
    $data = Bentleysoft\ES\Service::browse(0, 20, $query);

    return View::make('pako')->with( array('data'=>$data));
});
