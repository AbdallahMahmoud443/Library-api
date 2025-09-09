<?php

namespace App\Http\Controllers\V1\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BookStoreValidationRequest;
use App\Http\Requests\V1\BookUpdateValidationRequest;
use App\Http\Resources\V1\BookResource;
use App\Models\Book;
use App\Traits\BookCoverImage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class BookController extends Controller
{

    use BookCoverImage;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $books = Book::query()->paginate(10);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return BookResource::collection($books)->toResponse($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookStoreValidationRequest $request)
    {
        try {
            $validated_data = $request->validated();
            if ($request->hasFile('cover_image')) {
                $validated_data['cover_image'] = $this->uploadCoverImage($request->file('cover_image'));
            }
            $book = Book::create($validated_data);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return   new BookResource($book)->toResponse($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $book = Book::query()->with('author')->find($id);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return  new BookResource($book)->toResponse($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookUpdateValidationRequest $request, string $id)
    {
        try {
            $validated_data = $request->validated();
            $book = Book::find($id);
            if ($request->hasFile('cover_image')) {
                $validated_data['cover_image'] = $this->updateCoverImage($request->file('cover_image'), $book->cover_image);
            }
            $book->update($validated_data);
        } catch (\Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return   new BookResource($book)->toResponse($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::query()->find($id);
            if (!empty($book->cover_image)) {
                $this->deleteCoverImage($book->cover_image);
            }
            $book->delete();
        } catch (Throwable $th) {
            return new JsonResponse(
                data: ['message' => $th->getMessage()],
                status: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return new JsonResponse(
            data: ['message' => 'Book deleted successfully'],
            status: Response::HTTP_OK
        );
    }
}
