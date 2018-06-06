<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Response;
use Validator;
use JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
       
        // $user = Auth::user()->first();
        // $token = JWTAuth::fromUser($user);
        // return Response::json(compact('token'));
        return response([
            'status' => 'success',
            'data' => $user
           ], 200);
    
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        $user = \Auth::user();
        $customClaims=['user_id'=>$user->id, 'name'=>$user->name, 'email'=>$user->email];
        $token = JWTAuth::fromUser(\Auth::user(), $customClaims);
        return response([
            'status' => 'success',
            'token' => $token
        ]);
     
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
                'status' => 'success',
                'msg' => 'Logged out Successfully.'
            ], 200);
    }

    public function refresh()
    {
        return response([
         'status' => 'success'
        ]);
    }
}