<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


     ( )
   (.) (.)
     ) (
	(   )
	
	SMART DESIGNS
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('bloodgroup', function(Request $request) {
    	
    	$blood = \App\Models\Blood::All();
        return response()->json([
    	  'status_code' => 200,
    	  'Bgroup' => $blood,
    	]);
    });
    
    Route::post('store-donor', [App\Http\Controllers\DonorController::class, 'store']);
    
    Route::get('donors', [App\Http\Controllers\DonorController::class, 'list']);
});

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/signup', 'App\Http\Controllers\AuthController@register');

Route::get('countries', function() {
	
	$countries = \App\Models\Country::where('id','!=', '')->get(['id','name','phonecode']);
    return response()->json([
	  'status_code' => 200,
	  'countries' => $countries,
	]);
});


Route::get('states', function(Request $request) {
	$id = $request->input('id');
	$states = \App\Models\States::where('country_id', '=', $id)->get(['id','name']);
    return response()->json([
	  'status_code' => 200,
	  'states' => $states,
	]);
});

Route::get('cities', function(Request $request) {
		$id = $request->input('id');
	$cities = \App\Models\Cities::where('state_id', '=', $id)->get(['id','name']);
    return response()->json([
	  'status_code' => 200,
	  'cities' => $cities,
	]);
});



Route::get('district', function(Request $request) {
	
	//$geocode="http://api.nightlights.io/months/2010.3-2010.4/states/$nam/districts";
	$id = $request->input('id');
	$district = DB::table('districts')->where([
         ['state_id',$id],
        ])->get();
        
    return response()->json([
	  'status_code' => 200,
	  'district' => $district,
	]);
});
