<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomepageController@index');
Route::get('/contact', 'HomepageController@contact_page');
Route::get('/member_profile', 'MemberController@member_profile');
Route::get('/member_profile/logout', 'MemberController@member_logout');
Route::get('/member_transactions', 'MemberController@member_transactions');

Route::post('/member_login', 'MemberController@post_member_login');
Route::post('/member_add', 'MemberController@post_member_add');

