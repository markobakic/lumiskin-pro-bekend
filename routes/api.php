<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\DiscountCodeController;
use App\Http\Controllers\Api\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API (consumed by the Next.js site, with static fallback)
|--------------------------------------------------------------------------
*/
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);

Route::get('posts', [PostController::class, 'index']);
Route::get('posts/{slug}', [PostController::class, 'show']);

Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);

Route::post('discount/validate', [DiscountController::class, 'validate']);
Route::post('discount/use',      [DiscountController::class, 'use']);

/*
|--------------------------------------------------------------------------
| Admin API (Sanctum bearer token)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('credentials', [AuthController::class, 'credentials']);

        Route::get('products', [AdminProductController::class, 'index']);
        Route::post('products', [AdminProductController::class, 'store']);
        Route::get('products/{id}', [AdminProductController::class, 'show'])->whereNumber('id');
        Route::put('products/{id}', [AdminProductController::class, 'update'])->whereNumber('id');
        Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->whereNumber('id');

        Route::get('posts', [AdminPostController::class, 'index']);
        Route::post('posts', [AdminPostController::class, 'store']);
        Route::get('posts/{id}', [AdminPostController::class, 'show'])->whereNumber('id');
        Route::put('posts/{id}', [AdminPostController::class, 'update'])->whereNumber('id');
        Route::delete('posts/{id}', [AdminPostController::class, 'destroy'])->whereNumber('id');

        Route::get('newsletter', [AdminNewsletterController::class, 'index']);
        Route::get('discount-codes', [DiscountCodeController::class, 'index']);
    });
});
