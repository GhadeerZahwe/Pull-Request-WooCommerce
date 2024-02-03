<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WooCommerceController;

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

Route::get('getAllrequests',[WooCommerceController::class,'getRRPullRequests']);
Route::get('getOldPullRequests',[WooCommerceController::class,'getOldPullRequests']);

Route::get('getRRPullRequests',[WooCommerceController::class,'getRRPullRequests']);

Route::get('getSuccessPullRequests',[WooCommerceController::class,'getSuccessPullRequests']);

Route::get('getUnassignedPullRequests',[WooCommerceController::class,'getUnassignedPullRequests']);


