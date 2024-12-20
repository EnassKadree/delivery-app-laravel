<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
        $attributes=request()->validate([
            'phone'=>['required'],
            'password'=>['required'],
            'email'=>['required']
        ]);

        //attempt to login the user
        if(!Auth::attempt($attributes))
        {
            return response()->json([
                'success'=>false,
                'messeage'=>'Invalid credentials'
            ],401);
        }
        $phone=$request['phone'];
        $user=User::where('phone',$phone)->first();
        $token = $user->createToken('usertoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
