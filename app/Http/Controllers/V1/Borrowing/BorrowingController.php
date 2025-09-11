<?php

namespace App\Http\Controllers\V1\Borrowing;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BorrowingStoreValidationRequest;
use App\Http\Requests\V1\BorrowingUpdateValidationRequest;
use App\Http\Resources\V1\BorrowingResource;
use App\Models\Book;
use App\Models\Borrowing;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use function PHPUnit\Framework\isEmpty;

class BorrowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $borrowings = Borrowing::query()->with(['member', 'book'])->when($request->has('search'), function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('status', 'like', '%' . $request->search . '%')
                        ->orWhereHas('member', function ($query) use ($request) {
                            return $query->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })->paginate(10);
            return BorrowingResource::collection($borrowings)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BorrowingStoreValidationRequest $request)
    {

        try {
            $validated_date = $request->validated();

            $book = Book::find($validated_date['book_id']);

            if ($validated_date['status'] == 'borrowed') {
                if (!$book->isAvailableCopies()) {

                    return new JsonResponse(
                        data: ['message' => "Book is not available"],
                        status: Response::HTTP_BAD_REQUEST
                    );
                }
                $borrowing = Borrowing::create($validated_date);
                $book->borrowingBook();
            }
            return new BorrowingResource($borrowing)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $borrowing = Borrowing::with(['book', 'member'])->find($id);
            return new BorrowingResource($borrowing)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BorrowingUpdateValidationRequest $request, string $id)
    {

        try {

            $validated_date = $request->validated();
            $book = Book::find($validated_date['book_id']);
            $borrowing = Borrowing::where("id", $id)->first();
            if ($validated_date['status'] == 'returned') {
                $borrowing->update($validated_date);
                $book->returningBook();
            }
            return new BorrowingResource($borrowing)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $borrowing = Borrowing::with(['book', 'member'])->find($id);
        if ($borrowing->status == 'returned') {
            $borrowing->delete();
            return new JsonResponse(
                data: ['message' => 'Borrowing Deleted Successfully'],
                status: Response::HTTP_OK
            );
        }
        return new JsonResponse(
            data: ['message' => 'Borrowing is not returned'],
            status: Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
    public function overdueBorrowings(Request $request)
    {

        Borrowing::where('status', 'borrowed')
            ->where("due_date", "<=", now())
            ->update(['status' => 'overdue']);

        $overdueBorrowings = Borrowing::with(['book', 'member'])
            ->where('status', 'overdue')->get();

        if (empty($overdueBorrowings->toArray())) {
            return new JsonResponse(
                data: ['message' => 'No Overdue Borrowings'],
                status: Response::HTTP_OK
            );
        }
        return BorrowingResource::collection($overdueBorrowings)->toResponse($request);
    }
}
