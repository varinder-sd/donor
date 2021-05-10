<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|        
     ( )
   (.) (.)
     ) (
	(   )
	
	SMART DESIGNS
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    
    Route::get('donor', [App\Http\Controllers\DonorController::class, 'Alist'])->name('admin.donor');
    Route::get('deletedonor', [App\Http\Controllers\DonorController::class, 'destroy'])->name('admin.donor.destroy');
    
    Route::get('edit-donor', [App\Http\Controllers\DonorController::class, 'destroy'])->name('admin.donor.edit');
    
    Route::get('exceldonor', [App\Http\Controllers\BulkController::class, 'Alist'])->name('admin.importeddonor');
    
    Route::get('export-donor', [App\Http\Controllers\PDFController::class, 'export'])->name('admin.donor.export');
   Route::post('ajax/request/status', array('as' => 'ajax.request.status', 'uses' => 'App\Http\Controllers\DonorController@change_status'));
   
   
    Route::get('importdonor', 'App\Http\Controllers\DonorController@importView')->name('admin.import');
    Route::post('import', 'App\Http\Controllers\DonorController@import')->name('import');
});
