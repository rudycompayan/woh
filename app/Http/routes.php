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
Route::get('/gallery', 'HomepageController@gallery_page');
Route::get('/about', 'HomepageController@about_page');

Route::post('/contact', 'HomepageController@post_contact_page');

Route::get('/admin_profile', 'AdminController@admin_profile');
Route::get('/admin_login', 'AdminController@admin_login');
Route::get('/admin_profile/logout', 'AdminController@admin_logout');
Route::get('/withdrawal_request', 'AdminController@withdrawal_request');
Route::get('/short_codes', 'AdminController@short_codes');
Route::get('/gift_certificates', 'AdminController@gift_certificates');
Route::get('/redeem_gc', 'AdminController@redeem_gc');
Route::get('/klp_members', 'AdminController@klp_members');
Route::get('/klp_members_account', 'AdminController@klp_members_account');
Route::get('/gc_set', 'AdminController@gc_set');
Route::get('/change_password', 'AdminController@change_password');
Route::get('/admin_reports', 'AdminController@admin_reports');
Route::get('/admin_reports_release_codes', 'AdminController@release_codes');
Route::get('/admin_reports_unused_codes', 'AdminController@unused_codes');
Route::get('/admin_reports_cd_accounts', 'AdminController@cd_accounts');
Route::get('/admin_reports_klp_member_list', 'AdminController@klp_member_list');
Route::get('/admin_reports_redeemed_gc', 'AdminController@report_redeemed_gc');
Route::get('/admin_reports_withdrawals', 'AdminController@report_withdrawals');

Route::post('/admin_login', 'AdminController@post_admin_login');
Route::post('/short_codes', 'AdminController@post_short_codes');
Route::post('/withdrawal_request_update', 'AdminController@post_withdrawal_request_update');
Route::post('/gift_certificates', 'AdminController@post_gift_certificates');
Route::post('/redeem_gc', 'AdminController@post_redeem_gc');
Route::post('/print_gc', 'AdminController@post_print_gc');
Route::post('/gc_set', 'AdminController@post_gc_set');
Route::post('/change_password', 'AdminController@post_change_password');
Route::post('/admin_reports_cd_accounts_filter', 'AdminController@post_filter_cd_accounts');
Route::post('/admin_reports_withdrawals_filter', 'AdminController@post_filter_report_withdrawals');
Route::post('/admin_reports_release_codes_filter', 'AdminController@post_filter_release_codes');
Route::post('/admin_reports_unused_codes_filter', 'AdminController@post_filter_unused_codes');

Route::get('/member_profile', 'MemberController@member_profile');
Route::get('/member_profile/logout', 'MemberController@member_logout');
Route::get('/member_transactions', 'MemberController@member_transactions');
Route::get('/member_withdrawals', 'MemberController@member_withdrawals');

Route::post('/member_login', 'MemberController@post_member_login');
Route::post('/member_add', 'MemberController@post_member_add');
Route::post('/member_update', 'MemberController@post_member_update');
Route::post('/member_withdrawal', 'MemberController@post_member_withdrawal');
Route::post('/member_unilevel', 'MemberController@post_member_unilevel');
Route::post('/member_search', 'MemberController@post_member_search');


