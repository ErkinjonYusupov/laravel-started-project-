<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $search = request('search');
        return OrganizationResource::collection(
            $this->organization::with(['parent'])
            ->where('title', 'LIKE', "%$search%")
            ->paginate(10));
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

    public function parents(){
        return $this->organization::with(['parent'])->where('id', '!=', request('id'))->get();
    }

    public function setActive($id){
        $row=$this->organization::find($id);
        $row->active = !$row->active;
        $row->save();
        return response()->json(['message' => $row->active ? "Faollashtirildi" : "Nofaollashtirildi"],200);
    }

    public function getActiveOrganizations(){
        $auth = Auth::user();
        $organization = $this->organization::with(['children'])
        ->where('active', true)
        ->where('id', $auth->organization_id)
        ->first();
        $organizations[] = [
            'id' => $organization->id,
            'title' => $organization->title,
        ];
        return  $organizations = $this->recOrganizations($organizations, $organization->children, '-');
    }

    public function recOrganizations($organizations, $children, $line)
    {
        foreach($children as $child){
            $organizations[] = [
                'id' => $child['id'],
                'title' => $line . $child['title'],
            ];
            if($this->checkChild($child->children)){
                $line = "-$line";
                $organizations = $this->recOrganization($organizations, $child['children'], $line);
            }
        }
        return $organizations;
    }

    public function checkChild($children)
    {
        return count($children ?? []) > 0 ? true : false;
    }
}
