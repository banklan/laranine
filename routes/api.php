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


// user unprotected routes
Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('categories', 'CategoryController@getCategories');
    Route::get('locations', 'LocationController@getLocations');
    Route::post('confirm_user_email', 'UserController@confirmEmail');
    Route::get('get_featured_sps', 'ServiceController@getFeatServices');
    Route::get('get_popular_sps', 'ServiceController@getPopularServices');
    Route::get('get_popular_cats', 'CategoryController@getPopularCats');
});

// (auth user) protected routes
Route::group(['middleware' => 'jwt.auth',  'prefix' => 'auth'], function($router){
    Route::post('logout', 'AuthController@logout');
    Route::get('get_auth_service', 'ServiceController@getAuthService');
    Route::post('service/create', 'ServiceController@createService');
    Route::post('service/add_image', 'ServiceController@addServiceImage');
    Route::post('create_portfolio/{id}', 'PortfolioController@createPortfolio');
    Route::post('upload_pf_images/{id}', 'PortfolioController@uploadPortfolioFiles');
    Route::get('get_my_portfolio/{id}', 'PortfolioController@getPortfolio');
    Route::post('update_service', 'ServiceController@updateService');
    Route::post('update_portfolio/{id}', 'PortfolioController@updatePortfolio');
    Route::post('delete_portfolio_file/{id}', 'PortfolioController@deletePortfolioFile');
    Route::post('update_service_image', 'ServiceController@updateServiceImage');
    Route::post('confirm_current_pswd', 'UserController@confirmCurrentPassword');
    Route::post('update_user_profile_password', 'UserController@updateProfilePassword');
    Route::post('update_user_details', 'UserController@updateUserProfile');
    Route::post('update_profile_pic', 'UserController@updateprofilePicture');
    Route::post('delete_user_account', 'UserController@deleteUserAccount');
    // Route::get('get_auth_user_service', 'ServiceController@getAuthService');
});

//admin unprotected routes
Route::group(['middleware' => 'api', 'prefix' => 'auth-admin'], function ($router) {
    Route::post('login', 'AdminAuthController@login');
});


// admin protected routes
Route::group(['prefix' => 'auth-admin', 'middleware' => ['assign.guard:admin-api', 'jwt.auth']], function($router){
    Route::post('logout', 'AdminAuthController@logout');
    Route::get('all_users', 'AdminController@getPaginatedUsers');
    Route::post('search_for_users', 'AdminController@searchForUsers');
    Route::get('all_superusers', 'AdminController@getAllSuperUsers');
    Route::get('get_superuser/{id}', 'AdminController@getSuperUser');
    Route::post('update_superuser_profile_pic/{id}', 'AdminController@updateSuperUserProfPic');
    Route::post('update_superuser/{id}', 'AdminController@updateSuperUser');
    Route::post('upload_test_image', 'AdminController@uploadTestImage');
    Route::post('search_for_admins', 'AdminController@searchForAdmins');
    Route::get('get_user/{id}', 'AdminController@getUser');
    Route::post('change_user_status/{id}', 'AdminController@toggleUserStatus');
    Route::post('update_user_password/{id}', 'AdminController@updateUserPassword');
    Route::post('delete_user/{id}', 'AdminController@deleteUser');
    Route::post('update_user/{id}', 'AdminController@updateUser');
    Route::get('all_services', 'AdminController@getPaginatedServices');
    Route::get('get_service/{id}', 'AdminController@getService');
    Route::post('toggle_approval/{id}', 'AdminController@toggleApproval');
    Route::post('toggle_status/{id}', 'AdminController@toggleStatus');
    Route::post('toggle_plan/{id}', 'AdminController@togglePlan');
    Route::post('toggle_feature/{id}', 'AdminController@toggleFeature');
    Route::get('all_portfolios', 'AdminController@getPgntdPortfolios');
    Route::post('delete_pf/{id}', 'AdminController@deletePf');
    Route::get('get_portfolio/{id}', 'AdminController@getPortfolio');
    Route::post('update_pf/{id}', 'AdminController@updatePortfolio');
    Route::post('toggle_pf_approval/{id}', 'AdminController@togglePfApproval');
    Route::post('del_portfolio_file/{id}', 'AdminController@deletePfFile');
    Route::get('all_reviews', 'AdminController@getReviews');
    Route::post('delete_Service/{id}', 'AdminController@deleteService');
});
