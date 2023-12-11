<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/signUp', 'App\Http\Controllers\Api\DataController@signUp');
Route::post('/login', 'App\Http\Controllers\Api\DataController@login');
Route::post('/candidate_registeration', 'App\Http\Controllers\Api\DataController@candidate_registeration');
Route::post('/candidate_edit', 'App\Http\Controllers\Api\DataController@candidate_edit');
Route::post('/employer_registeration', 'App\Http\Controllers\Api\DataController@employer_registeration');
Route::post('/employer_edit', 'App\Http\Controllers\Api\DataController@employer_edit');

Route::post('/post_job', 'App\Http\Controllers\Api\JobController@post_job');
Route::get('/job_list', 'App\Http\Controllers\Api\JobController@job_list');
Route::get('/get_employers', 'App\Http\Controllers\Api\JobController@get_employers');
Route::post('/apply', 'App\Http\Controllers\Api\JobController@apply');
Route::get('/category_list', 'App\Http\Controllers\Api\JobController@category_list');
Route::get('/candi_activity/{id}', 'App\Http\Controllers\Api\JobController@candi_activity');
Route::get('/employer_activity/{id}', 'App\Http\Controllers\Api\JobController@employer_activity');
Route::get('/featured_jobs', 'App\Http\Controllers\Api\JobController@featured_jobs');
