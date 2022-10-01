<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserRuleResource;
use App\Models\UserRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {

        // return Auth::user();
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
            [
                'full_name' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required',
                'organization_id' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $this->user::create([
                'full_name' => $request->full_name,
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
                'message' => "auth qilinmagan"
                // 'message' => $th->getMessage()
            ], 500);
        }
    }



    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['username', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Username & Password does not match with our record.',
                ], 401);
            }

            $user = $this->user::where('username', $request->username)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("erick")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function authUser(){
        return UserRuleResource::collection($this->user::with(['user_rules.rule',
        'organization:id,title'])
        ->where('id', auth()->id())
        ->get());
    }
}
