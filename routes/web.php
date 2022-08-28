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
*/

//Route::get('/', function () {
//    return view('home');
//});
Route::get('/', [\App\Http\Controllers\RbcParseController::class, 'index']);
Route::get('/news/{publish_date}', [\App\Http\Controllers\RbcParseController::class, 'news'])
    ->where(['publish_date'=>'[0-9]+']);

Route::get('/rbk',[\App\Http\Controllers\RbcParseController::class, 'parseListNews']);
//Route::get('/conv',[\App\Http\Controllers\RbcParseController::class, 'conv']);
Route::get('/clean-no-texts',[\App\Http\Controllers\RbcParseController::class, 'purgeNoTexts']);

