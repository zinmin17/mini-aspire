<?php

use Illuminate\Http\Request;

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


/* 
|---------------------------
| user (client) routes
|---------------------------
*/

//http://localhost/aspire/public/api/user/register
Route::group(['prefix'=>'user'], function(){

    /* guest routes */
    Route::post('register', 'API\UserController@register');
    Route::post('login', 'API\UserController@login');

    /* authenticated routes */
    Route::group(['middleware' => 'auth:api'], function(){
        Route::post('details', 'API\UserController@details');
        Route::post('loan/request/create', 'API\LoanRequestController@create');
        Route::post('loan/request/all', 'API\LoanRequestController@all');
        Route::post('loan/view', 'API\LoanController@viewOfferedLoans');
        Route::post('loan/respond', 'API\LoanController@respondOffer');
        Route::post('repayment/make', 'API\RepaymentController@makePayment');
        
    });
});  

/* 
|---------------------------------------
| admin (loan approval/creator) routes
|---------------------------------------
*/

//http://localhost/aspire/public/api/admin/register
Route::group(['prefix'=>'admin'], function(){

    /* guest routes */
    Route::post('register', 'API\AdminController@register');
    Route::post('login', 'API\AdminController@login');

    /* authenticated routes */
    Route::group(['middleware' => 'auth:admin'], function(){
        Route::post('loan/offer', 'API\LoanController@offerLoan');
        Route::post('loan/details', 'API\LoanController@details');
        Route::post('loan/overdue', 'API\LoanController@overdue');
    });
}); 