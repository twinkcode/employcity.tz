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


Route::get('/', [\App\Http\Controllers\NewsParseController::class, 'index']);

Route::get('/news/{publish_date}', [\App\Http\Controllers\NewsParseController::class, 'news'])
    ->where(['publish_date'=>'[0-9]+']);

Route::get('/rbk',[\App\Http\Controllers\NewsParseController::class, 'parseListNews']);

//Route::get('/clean-no-texts',[\App\Http\Controllers\NewsParseController::class, 'purgeNoTexts']);

