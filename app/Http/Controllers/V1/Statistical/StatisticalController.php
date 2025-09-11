<?php

namespace App\Http\Controllers\V1\Statistical;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Member;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatisticalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return new JsonResponse(
            [
                "total_book" => Book::count(),
                "total_authors" => Author::count(),
                "total_members" => Member::count(),
                "books_borrowed" => Borrowing::where('status', 'borrowed')->count(),
                "books_overdue" => Borrowing::where('status', 'overdue')->count(),
            ],
            200
        );
    }
}
