<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsParseController;
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


Route::get('/', [NewsParseController::class, 'index']);

Route::get('/news/{publish_date}', [NewsParseController::class, 'news'])->where(['publish_date'=>'[0-9]+']);

Route::get('/rbk',[NewsParseController::class, 'parseListNews']);

Route::get('/clean-no-texts',[NewsParseController::class, 'purgeNoTexts']);

Route::get('/truncate-base',[NewsParseController::class, 'baseTruncate']);

