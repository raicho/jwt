<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class TokenManager extends Controller
{
     /**
     * generateToken function
     *
     * @param [type] $user
     * @return string
     */

    public function createNewToken($user) {
        return $this->generateToken($user);
    }

    private function generateToken($user): string {
        $currentDateTime = Carbon::now();
        $headers = array('alg'=>'HS256','typ'=>'JWT');
        $payload = array('sub'=>$user->phone,'name'=> $user->name, 'exp'=>(time() + env('APP_TOKEN_EXPORED_TIME')));
        $jwt = $this->generate_jwt($headers, $payload);
 
        // If we need to store tokens and more info about user in database //
        // DB::table('personal_access_tokens')->insert([
        //     'tokenable_type' => 'bearer',
        //     'name' => 'api-token',
        //     'token' => $jwt,
        //     'created_at' => $currentDateTime,
        // ]);    
        return $jwt;
    }

    /**
     * base64url_encode function
     *
     * @param [type] $str
     * @return string
     */
    private function base64url_encode($str):string {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * generate_jwt function
     *
     * @param [type] $headers
     * @param [type] $payload
     * @return string
     */
    private function generate_jwt($headers, $payload):string {
        $secret = env('APP_TOKEN_SECRET');
        $headers_encoded = $this->base64url_encode(json_encode($headers));
        $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = $this->base64url_encode($signature);
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        return $jwt;
    }

    /**
     * tokenIsValid function
     *
     * @param [type] $jwt
     * @return boolean
     */
    public function tokenIsValid($jwt):bool {
        $secret = env('APP_TOKEN_SECRET');
        $tokenParts = explode('.', $jwt);
        // check for empty parts
        if(!isSet($tokenParts[1]) || !isSet($tokenParts[2])) {
            return false;
        }
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;
        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
        $base64_url_signature = $this->base64url_encode($signature);
        $is_signature_valid = ($base64_url_signature === $signature_provided);
        if ($is_token_expired || !$is_signature_valid) {
            return false;
        } else {
            return true;
        }
    }

    public function getUserInfoByJwt($jwt) {
        $secret = env('APP_TOKEN_SECRET');
        $tokenParts = explode('.', $jwt);
        if(!isSet($tokenParts[1]) || !isSet($tokenParts[2])) {
            return false;
        }
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $userData = json_decode($payload, true);
        $user = DB::connection('db2')->table("users")->where(['name' => $userData['name'], 'phone' => $userData['sub']])->first();
        return $user;
    }

}
