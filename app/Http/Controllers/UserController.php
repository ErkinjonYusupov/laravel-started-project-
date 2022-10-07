<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
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
        $organization_id = request('organization_id', '');
        return UserResource::collection(
            $this->user::where('full_name', 'LIKE', "%$search%")
            ->where('organization_id', $organization_id ? '=' : '!=', $organization_id)
            ->paginate(20)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
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
            $user->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'organization_id' => $request->organization_id,
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
