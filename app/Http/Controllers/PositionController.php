<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PositionController extends Controller
{

    private Position $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    public function index()
    {
        $search = request('search');
        return PositionResource::collection(
            $this->position::where('title', 'LIKE', "%$search%")->paginate(10));
    }


    public function store(Request $request)
    {
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
            $this->position::create([
                'title' => $request->title,
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


    public function update(Request $request, Position $position)
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
            $position->update([
                'title' => $request->title,
            ]);
            return response()->json([
                'message' => 'Lavozim muvaffaqiyatli tahrirlandi',
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
    public function setActive($id){
        $row=$this->position::find($id);
        $row->active = !$row->active;
        $row->save();
        return response()->json(['message' => $row->active ? "Faollashtirildi" : "Nofaollashtirildi"],200);
    }
    public function getActivePositions(){
        return $this->position::where('active', true)->get();
    }
}
