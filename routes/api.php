<?php

use App\Http\Controllers\V1\Author\AuthorController;
use App\Http\Controllers\V1\Book\BookController;
use App\Http\Controllers\V1\Borrowing\BorrowingController;
use App\Http\Controllers\V1\Member\MemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(
    static function () {
        Route::apiResource('authors', AuthorController::class);
        Route::apiResource('books', BookController::class);
        Route::apiResource('members', MemberController::class);
        Route::apiResource('borrowings', BorrowingController::class);
    }
);
