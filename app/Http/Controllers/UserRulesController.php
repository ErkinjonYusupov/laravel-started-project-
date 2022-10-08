<?php

namespace App\Http\Controllers;

use App\Http\Resources\RulesResource;
use App\Models\UserRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserRulesController extends Controller
{

    private UserRule $user_rule;

    public function __construct(UserRule $user_rule)
    {
        $this->user_rule = $user_rule;
    }

    public function index()
    {

    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
    public function roles(){
        $user_id = request('userId');
        $search = request('search');
        return RulesResource::collection(
        $this->user_rule::with('rule')
        ->where('user_id', $user_id)
        ->whereHas('rule', function($q) use ($search){
            $q->where('title', 'LIKE', "%$search%");
        })
        ->paginate(20));
    }
    public function no_roles(){
        $user_id = request('userId');
        $search = request('search');
        $userRulesId = $this->user_rule::where('user_id', $user_id)->pluck('rule_id');
        $userNotRules = $this->user_rule::with(['rule'])
            ->where('user_id', auth()->id())
            ->whereHas('rule', function($q) use ($search){
                $q->where('title', 'LIKE', "%$search%");
            })
            ->whereNotIn('rule_id', $userRulesId)
            ->groupBy('rule_id')
            ->select("rule_id")
            ->paginate(20);
        return RulesResource::collection($userNotRules);
    }
    public function add(Request $request){
        $validator = Validator::make($request->all(),
            [
                'rules' => 'required',
                'user_id' => 'required',
            ],[
                'rules.required' => 'Rullarni tanlang',
                'user_id.required' => 'Hodimni tanlang'
            ]);

            if ($validator->fails())
                return response()->json(['message' => $validator->getMessageBag()], 400);

                $rules=$request->get('rules');
                $user_id=$request->get('user_id');
        DB::beginTransaction();
        try{
            $array = [];
            foreach($rules as $rule){
                $array[] = [
                    'rule_id'=>$rule['id'],
                    'user_id'=>$user_id
                ];
            }
            $this->user_rule::insert($array);
            DB::commit();
            return response()->json(['message'=>"Ma'lumot saqlandi"]);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()

            ], 500);
        }
    }
    public function remove(Request $request){
        $validator = Validator::make($request->all(),
            [
                'rules' => 'required',
                'user_id' => 'required',
            ],[
                'rules.required' => 'Rullarni tanlang',
                'user_id.required' => 'Hodimni tanlang'
            ]);

            if ($validator->fails())
                return response()->json(['message' => $validator->getMessageBag()], 400);
                $rules=$request->get('rules');
                $userId=$request->get('user_id');
            DB::beginTransaction();
        try{
            $this->user_rule->where('user_id', $userId)
            ->whereIn('rule_id', $rules)->delete();
            DB::commit();
            return response()->json(['message'=>"Ma'lumot saqlandi"]);
        }catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()

            ], 500);
        }
    }
}
