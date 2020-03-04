<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'username' => 'required'
        ]);

        try {
            $user = new User;
            $user->email = $request->input('email');
            $user->username = $request->input('username');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->save();

            //return successful response
            return $this->jsonify(1,'User Created',201, $user);

        } catch (\Exception $e) {
            //return error message
            return $this->jsonify(0,'User Registration Failed!', 409);
        }
    }

    public function login(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'user' => Auth::user(),
            'token' => $token
        ],200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }

    public function checkToken(){
        return response()->json([
            'msg' => "success"
        ],200);
    }


}