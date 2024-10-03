<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'name'      =>  'required|unique:users,string|max:30',
                'email'     =>  'required|unique:users|string|email|max:100',
                `password`  =>  'required|string|min:8',
            ]);

                if($validator->fails()){
                    $resultError = [
                        'httpcode'  =>  400,
                        'error'     =>  true,
                        'data'      =>  [
                            'message'   =>'Validation error.',
                            'errors'    =>  $validator->errors(),
                        ],
                    ];
                        return response()->json($resultError,400);                        
                }
                    User::create([
                        'name'      =>  $request->name,
                        'email'     =>  $request->email,
                        'password'  =>  Hash::make($request->password),
                    ]);

                    $result = [
                        'httpcode'  =>  200,
                        'error'     =>  false,
                        'data'      =>  [
                            'message'   =>'User created successfully.',
                        ],
                    ];
                        return response()->json($result,200);

        } catch (\Throwable $th) {
            $resultError = [
                'httpcode'  =>  500,
                'error'     =>  true,
                'data'      =>  [
                    'message'   =>'Failed to create user.',
                ],
            ];
                return response()->json($resultError,500);
        }
    }
}
