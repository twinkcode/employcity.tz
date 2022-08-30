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

Route::controller(App\Http\Controllers\NewsParseController::class)->group(function () {

    Route::get('/', 'index');

    Route::get('/news/{publish_date}', 'news')->where(['publish_date' => '[0-9]+']);

    Route::get('/rbk', 'parseListNews');

    Route::get('/clean-no-texts', 'purgeNoTexts');

    Route::get('/truncate-base', 'baseTruncate');

});

Route::controller(App\Http\Controllers\VueViteController::class)->prefix('p2')->group(function () {

    Route::get('/', 'index');
    Route::get('/read-data', 'readData');

});

