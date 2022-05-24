<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TokenManager;
class Api extends Controller
{
    /**
     * login function
     *
     * @param Request $request
     * @return Json Response
     */

    public function login(Request $request) {
        //TODO: encode fields 
        $phone = $request->get('phone');
        $name = $request->get('name');
        $user = DB::connection('db2')->table("users")->where(['name' => $name, 'phone' => $phone])->first();
   
        // check user exist //
        if($user !== null) {
            $tokenManager = new TokenManager();
            $token = $tokenManager->createNewToken($user);
            return response()->json(['description' => 'success', 'status' => '200', 'api_token' => $token], 200);

        }
        return response()->json(['description' => 'user not found', 'status' => '404'], 404);
    }

 

   
}
