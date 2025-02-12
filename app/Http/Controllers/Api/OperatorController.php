<?php

namespace App\Http\Controllers\Api;

use App\Enums\Alarms_type;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OperatorController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addZone(Request $request)
    {
        DB::table('users_zones')->insert($request->request->all());
        return $this->sendResponse($request->request->all(), __('messages.user.addZone'), 201);
    }
    public function delZone(Request $request)
    {
        $parametros=$request->request->all();
        DB::table('users_zones')->where('zone_id','=',$parametros['zone_id'])->where('user_id','=',$parametros['user_id'])->delete();
        return $this->sendResponse($parametros, __('messages.user.delZone'), 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
       
        dd(array_column(Alarms_type::cases(),'name'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
