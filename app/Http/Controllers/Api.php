<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\TokenManager;
use Illuminate\Http\JsonResponse;

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
            return response()->json(['access_token' => $token, 'expires_in' => env('APP_TOKEN_EXPORED_TIME')], 200);

        }
        return response()->json(['description' => 'user not found', 'status' => '404'], 404);
    }


    /**
     * openapi function
     *
     * @return JsonResponse
     */
    public function openapi(): JsonResponse {
        $data = [
            'openapi' => env('APP_API_VERSION')
        ];
        return response()->json($data, 200);
    }

    /**
     * info function
     *
     * @return JsonResponse
     */
    public function info(): JsonResponse {
        $data = [
            'version' => env('APP_API_VERSION'),
            'title' => 'Iteco Api',
            'description' => 'This document contains the API descriprion',
            'contact'  => [                
                'name'=> 'TODO: need more info',
                'url'=> 'https://iteco.bg/',
                'email'=> 'office@iteco.bg',
            ],
            'license' => [
                'name' => 'Cloud Signature Consortium License',
                'url'=> 'https://cloudsignatureconsortium.org/'
            ]
        ];
        return response()->json($data, 200);
    }


 

   
}
