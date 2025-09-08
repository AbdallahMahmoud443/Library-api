<?php

namespace App\Http\Controllers\V1\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AuthorValidationRequest;
use App\Http\Resources\V1\AuthorResource;
use App\Models\Author;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $authors = Author::query()->with(['books'])->paginate(10);
        } catch (\Throwable $th) {

            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return  AuthorResource::collection(resource: $authors)
            ->toResponse(request: $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthorValidationRequest $request)
    {
        try {
            $author = Author::create($request->validated());
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return  new AuthorResource(resource: $author)
            ->toResponse(request: $request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
        try {
            $author = Author::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return  new AuthorResource(resource: $author)
            ->toResponse(request: $request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AuthorValidationRequest $request, string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->update($request->validated());
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return  new AuthorResource(resource: $author)
            ->toResponse(request: $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json(
            [
                "message" => "Author deleted successfully"
            ],
            200
        );
    }
}
