<?php

namespace App\Http\Controllers\V1\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\MemberStoreValidationRequest;
use App\Http\Requests\V1\MemberUpdateValidationRequest;
use App\Http\Resources\V1\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try {
            $members = Member::query()->with(['activeBorrowings'])->when($request->has('search'), function ($query) use ($request) {
                return $query->whereAny(['name', 'email'], 'like', '%' . $request->search . '%');
            })->when($request->has('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })->paginate(10);

            return  MemberResource::collection($members)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MemberStoreValidationRequest $request)
    {

        try {
            $validated_data = $request->validated();
            $validated_data["status"] = "active";
            $created_member = Member::create($validated_data);
            return new MemberResource($created_member)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        try {
            $member = Member::with(["activeBorrowings", "borrowings"])->findOrFail($id);

            return new MemberResource($member)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MemberUpdateValidationRequest $request, string $id)
    {
        try {
            $validated_data = $request->validated();
            $member = Member::findOrFail($id);
            $member->update($validated_data);
            return new MemberResource($member)->toResponse($request);
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => $th->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $member = Member::findOrFail($id);
            if (!$member->activeBorrowings->count() > 0) {
                $member->delete();
                return new JsonResponse(['message' => 'Member deleted successfully'], 200);
            } else {
                return new JsonResponse(['message' => 'Member has active borrowings'], 400);
            }
        } catch (\Throwable $th) {
            return new JsonResponse(['message' => $th->getMessage()], 500);
        }
    }
}
