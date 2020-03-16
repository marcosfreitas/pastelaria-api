<?php

use Illuminate\Http\JsonResponse;
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

Route::get('/', function () {
	return response()->json([
		'error' => 0,
		'code' => 'welcome',
		'description' => "Winning is not everything, but the effort to win is. - Zig Ziglar"
	], JsonResponse::HTTP_OK);
})->name('login');

Route::apiResources([
    'clients' => 'ClientController',
    'orders' => 'OrderController',
    'pastels' => 'PastelController'
]);
