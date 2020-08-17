<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['prefix'=> 'files'], function(){
    // Route::post('post/id', 'ApiProController@storeProCrd');
    Route::post('post/id', 'ApiProController@storeProCrd');
    Route::post('post/verification', 'ApiProController@saveVerifyPic');
    Route::post('post/files', 'ApiProController@saveFiles');
    
    /* this is used for customer app */
    Route::post('/upload/image', 'Api2Controller@saveProjectFile');

});