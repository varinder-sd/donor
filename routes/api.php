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


Route::get('app_version', function() {
	
	$version = DB::table('app_version')->latest('id')->first();
    return response()->json([
	  'status_code' => 200,
	  'vesrion' => $version->version,
	]);
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['auth:sanctum']], function() {
    
    Route::get('/user', function (Request $request) {
        
    $user = \App\Models\User::with('country','state','district','city')->where('id','=',$request->user()->id)->first();
    
    $user->avatar = 'storage/'.$user->avatar;
    
         return response()->json([
    	  'status_code' => 200,
    	  'user' => $user,
    	]);
    });
    
    
    Route::post('users/update',[App\Http\Controllers\UserController::class, 'editUser']);
    
    
    Route::get('bloodgroup', function(Request $request) {
    	
    	$blood = \App\Models\Blood::All();
        return response()->json([
    	  'status_code' => 200,
    	  'Bgroup' => $blood,
    	]);
    });
    
    Route::get('comments', function(Request $request) {
    	
    	$comment = DB::table('argument')->where([
         ['status',1],
        ])->get();
        
        return response()->json([
    	  'status_code' => 200,
    	  'comment' => $comment,
    	]);
    });
    
    
    Route::post('store-doctor', [App\Http\Controllers\DoctorController::class, 'store']);
    
    Route::post('store-donor', [App\Http\Controllers\DonorController::class, 'store']);
    
    Route::post('store-patient', [App\Http\Controllers\PatientController::class, 'store']);
    
    Route::post('editPatient', [App\Http\Controllers\PatientController::class, 'editPatient']);
    
    Route::get('donors', [App\Http\Controllers\DonorController::class, 'list']);
    
    Route::post('donors-search', [App\Http\Controllers\DonorController::class, 'search']);
    
    Route::post('donor_status', [App\Http\Controllers\DonorController::class, 'donor_status']);
    
    Route::get('patients', [App\Http\Controllers\PatientController::class, 'list']);
});



Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/signup', 'App\Http\Controllers\AuthController@register');

Route::post('/verify_user', 'App\Http\Controllers\AuthController@verify_user');

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
		
	$dis = $request->input('district_id');
	
    if(empty($id) && empty($id)){
	    $cities = \App\Models\Cities::where('country_id', '=', 101)->get(['id','name']);
	    
	   return response()->json([
    	  'status_code' => 200,
    	  'cities' => $cities,
    	]);
	}
		
	$cities = \App\Models\Cities::where('district_id', '=', $dis)->get(['id','name']);
	
	if($cities->isEmpty()){
	    $cities = \App\Models\Cities::where('state_id', '=', $id)->get(['id','name']);
	}
	
    return response()->json([
	  'status_code' => 200,
	  'cities' => $cities,
	]);
});



Route::get('district', function(Request $request) {
	
	//$geocode="http://api.nightlights.io/months/2010.3-2010.4/states/$nam/districts";
/*	
	$geocode="http://api.nightlights.io/months/2010.3-2010.4/states/uttar-pradesh/districts";

 $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $geocode);
    $contents = curl_exec($c);
    curl_close($c);


        $output= json_decode($contents);

 foreach($output as $district){

echo $n = str_replace('uttar-pradesh-','',$district->key);

echo "<br>";
	$shopOwner = DB::table('districts')->where([
    ['name',$n], ['state_id',4022],
])->get();
print_r($shopOwner);
if ($shopOwner->isEmpty()) {
   DB::table('districts')->insert(array('name'=> $n,'state_id'=>4022)); 
} 
 }
	
	
die;	
	*/
	
	
	$id = $request->input('id');
	$district = DB::table('districts')->where([
         ['state_id',$id],
        ])->get();
        
    return response()->json([
	  'status_code' => 200,
	  'district' => $district,
	]);
});
