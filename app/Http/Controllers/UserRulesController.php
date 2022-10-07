<?php

namespace App\Http\Controllers;

use App\Http\Resources\RulesResource;
use App\Models\UserRule;
use Illuminate\Http\Request;

class UserRulesController extends Controller
{

    private UserRule $user_rule;

    public function __construct(UserRule $user_rule)
    {
        $this->user_rule = $user_rule;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    public function update(Request $request, $id)
    {
        //
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
    public function roles(){
        $user_id = request('userId');
        return RulesResource::collection(
        $this->user_rule::with('rule')->where('user_id', $user_id)->paginate(20));
    }
    public function no_roles(){
        $user_id = request('userId');
        return RulesResource::collection(
        $this->user_rule::with('rule')->where('user_id', "!=", $user_id)->paginate(20));
    }
}
