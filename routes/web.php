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

Route::get('login', 'ProfileController@login')->name('login_fb');
Route::get('portal', 'ProfileController@index')->name('portal_perfil');
Route::post('perfil', 'ProfileController@profile')->name('detalle_perfil');
