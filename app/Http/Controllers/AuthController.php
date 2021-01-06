<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {  

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:55',
            'lastname'  => 'required|max:55',
            'email'     => 'required|email',
            'image'     => 'required|image|mimes:jpeg,jpg,svg,png,gif|max:5048',
            'password'  => 'required|min:6',
            'username'  => 'required|unique:users|min:5|max:55'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = [
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'username' => $request->username,
        'image' => null,
        'email'    => $request->email,
        'password' => $request->password,
        
    ];
        if($request->image)
       {
           $image = $request->image;
           $name = time().'_' . $image->getClientOriginalName();
           $filePath = $request->file('image')->storeAs('', $name, 'public');
           $user['image'] = $name;  
        }
        User::create($user);
        
        return response()->json('Successfully added');
    }

    public function login(Request $request)
    {
        $credentials = request(['username', 'password']);
        $validator = Validator::make($request->all(), [
            'password'  => 'required|min:6',
            'username'  => 'required|min:5|max:55',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 401);
        }
         else if (!$token = auth()->attempt($credentials)) {
            
            return response()->json(['error' => 'Unauthorized']);
            
        }else {
        return $this->respondWithToken($token);
        }
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        $user= auth()->user();
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60*24*7,
            'user' => $user,
        ]);
    }
}