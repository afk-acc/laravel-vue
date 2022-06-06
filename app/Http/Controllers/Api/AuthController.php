<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    //



    public function login(Request $request){
        $validate = Validator::make($request->all(),[
            'email'=>'required|string|email|max:255',
            'password'=>'required|between:8,255'
        ]);
        if($validate->fails())
            return response(['errors'=>$validate->errors()->all()],422);
        $passwordGrantClient = Client::where('password_client',1)->first();
        $data = [
          'grant_type'=>'password',
            'client_id'=>$passwordGrantClient->id,
            'client_secret'=>$passwordGrantClient->secret,
            'username'=>$request->email,
            'password'=>$request->password,
            'scope'=>'*'
            ];
        $token_request = Request::create('/oauth/token','post',$data);
        return app()->handle($token_request);

    }

    public function register(Request $request){
        $validate = Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|between:8,255|confirmed'
            ]);
        if($validate->fails())
            return response(['errors'=>$validate->errors()->all()],422);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        if(!$user)
            return response()->json(['success'=>false,'message'=>"register failed"],500);
        return response()->json(['success'=>true,'message'=>"register successed"],500);

    }

}
