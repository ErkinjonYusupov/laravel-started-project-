<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function index()
    {
        $search = request('search');
        $organization_id = request('organization_id', 0);
        $auth = Auth::user();
        $treeOrgsId = $this->getTreeOrganizationId($auth->organization_id);
        $users=$this->user::where('full_name', 'LIKE', "%$search%")
        ->whereIn('organization_id', $treeOrgsId)
        ->where('organization_id', $organization_id ? '=' : '!=', $organization_id)
        ->paginate(20);

        return UserResource::collection($users);
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => ['required', Rule::unique('users')->where(
                    function ($q) use ($user) {
                        $q->where('username', $user->username)
                            ->where('id', '!=', $user->id);
                    }
                )],
                'full_name' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'organization_id' => 'required',
            ],[
                'username.unique' => 'Login qiyinroq bo\'lishi kerak'
            ]);

            if ($validator->fails())
                return response()->json(['message' => $validator->getMessageBag()], 400);
        try {

            $user->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'organization_id' => $request->organization_id,
                'position_id' => $request->position_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()

            ], 500);
        }
    }


    public function destroy($id)
    {
        //
    }
}
