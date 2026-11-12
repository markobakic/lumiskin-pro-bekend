<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Api\Admin\DiscountCodeController;
use App\Http\Controllers\Api\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ProductMediaController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
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

// Komentari za blog postove
Route::get('posts/{slug}/comments', [CommentController::class, 'index']);
Route::post('posts/{slug}/comments', [CommentController::class, 'store']);

Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);

Route::post('discount/validate', [DiscountController::class, 'validate']);
Route::post('discount/use',      [DiscountController::class, 'use']);

// Porudžbine iz korpe (max 10 po minutu sa iste IP adrese)
Route::post('orders', [OrderController::class, 'store'])->middleware('throttle:10,1');

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
        // Upload medija za proizvode (mora pre {id} ruta da nema konflikta)
        Route::post('products/media/image', [ProductMediaController::class, 'uploadImage']);
        Route::post('products/media/video', [ProductMediaController::class, 'uploadVideo']);

        Route::get('posts', [AdminPostController::class, 'index']);
        Route::post('posts', [AdminPostController::class, 'store']);
        Route::get('posts/{id}', [AdminPostController::class, 'show'])->whereNumber('id');
        Route::put('posts/{id}', [AdminPostController::class, 'update'])->whereNumber('id');
        Route::delete('posts/{id}', [AdminPostController::class, 'destroy'])->whereNumber('id');
        // Upload/uklanjanje slike posta
        Route::post('posts/{id}/image', [AdminPostController::class, 'uploadImage'])->whereNumber('id');
        Route::delete('posts/{id}/image', [AdminPostController::class, 'deleteImage'])->whereNumber('id');

        // Upload slobodnih medija (slike sekcija)
        Route::post('media', [\App\Http\Controllers\Api\Admin\MediaController::class, 'upload']);

        Route::get('newsletter', [AdminNewsletterController::class, 'index']);
        Route::delete('newsletter/{id}', [AdminNewsletterController::class, 'destroy'])->whereNumber('id');

        Route::get('orders', [AdminOrderController::class, 'index']);
        Route::get('orders/{id}', [AdminOrderController::class, 'show'])->whereNumber('id');
        Route::patch('orders/{id}', [AdminOrderController::class, 'update'])->whereNumber('id');
        Route::delete('orders/{id}', [AdminOrderController::class, 'destroy'])->whereNumber('id');

        Route::get('discount-codes', [DiscountCodeController::class, 'index']);
        Route::delete('discount-codes/{id}', [DiscountCodeController::class, 'destroy'])->whereNumber('id');

        // Moderacija komentara
        Route::get('comments', [AdminCommentController::class, 'index']);
        Route::patch('comments/{id}/approve', [AdminCommentController::class, 'approve'])->whereNumber('id');
        Route::patch('comments/{id}/reject', [AdminCommentController::class, 'reject'])->whereNumber('id');
        Route::delete('comments/{id}', [AdminCommentController::class, 'destroy'])->whereNumber('id');
    });
});
