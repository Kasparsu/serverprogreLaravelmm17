<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return view('welcome');
});
Route::get('/about', function (){
    return view('about', [
        'articles' => \App\Article::latest()->limit(3)->get()
    ]);
});
Route::get('articles/{article}', 'ArticlesController@show');
//Route::get('/vue', function (){
//    return view('vuetest');
//});
Route::get('/contacts', function (){
    return view('contacts');
});
Route::get('blog/{post}', 'PostsController@show');
