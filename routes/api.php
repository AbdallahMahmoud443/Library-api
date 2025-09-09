<?php

use App\Http\Controllers\V1\Author\AuthorController;
use App\Http\Controllers\V1\Book\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    static function () {
        Route::apiResource('authors', AuthorController::class);
        Route::apiResource('books', BookController::class);
    }
);
