<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/clear-cache', function() {Illuminate\Support\Facades\Artisan::call('cache:clear');return "Cache is cleared";});

    $router->group(['middleware' => 'guest.lang'], function () use ($router) {
        $router->post('auth/login',  [AuthController::class,'login'])->name("login");
        $router->get('products', [ProductController::class,'index']);
        $router->get('products/load-categories', [ProductController::class,'getAllCategories']);
        $router->get('products/{id}', [ProductController::class,'getProductById']);
    });

    Route::group( ['middleware' => ['jwt.verify']], function () use ($router){

        $router->patch('products/{id}', [ProductController::class,'update']);
        $router->post('products', [ProductController::class,'create']);
        $router->delete('products/{id}', [ProductController::class,'delete']);

    });
    
   
    