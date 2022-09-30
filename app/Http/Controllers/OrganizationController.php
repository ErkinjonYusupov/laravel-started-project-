<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationController extends Controller
{

    private Organization $organization;

    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function index()
    {
        return OrganizationResource::collection($this->organization::with(['parent'])->paginate(10));
    }


    public function store(OrganizationRequest $request)
    {
        //Validated
        $validateUser = Validator::make($request->all(),
        [
            'title' => 'required',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        try {
            $this->organization::create([
                'title' => $request->title,
                'parent_id' => $request->parent_id,
            ]);
            return response()->json([
                'message' => 'Organizatsiya muvaffaqiyatli yaratildi',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, Organization $organization)
    {
        //Validated
        $validateUser = Validator::make($request->all(),
        [
            'title' => 'required',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        try {
            $organization->update([
                'title' => $request->title,
                'parent_id' => $request->parent_id,
            ]);
            return response()->json([
                'message' => 'Organizatsiya muvaffaqiyatli tahrirlandi',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        //
    }
}
