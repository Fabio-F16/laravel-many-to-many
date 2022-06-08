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
*/
// api predisposta per gli utenti che hanno fatto i login, restituisce l'utente autorizzato
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//  per rispettare rest inseriamo /posts
// la rotta restituisce l'api del Postcontroller alla funzione index
Route::get('/posts', 'Api\PostController@index')->name('posts.index');
