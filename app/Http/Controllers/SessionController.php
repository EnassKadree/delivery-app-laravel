<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('phone', $attributes['phone'])->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'messages' => ['The provided phone number is incorrect.']
            ], 401);
        }
        
        if ($user->email !== $attributes['email']) {
            return response()->json([
                'success' => false,
                'messages' => ['The provided email does not match the phone number.']
            ], 401);
        }
        
        if (!Hash::check($attributes['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'messages' => ['The provided password is incorrect.']
            ], 401);
        }
        
        // attempt to login the user
        
        $phone=$request['phone'];
        $user=User::where('phone',$phone)->first();
        $token = $user->createToken('usertoken')->plainTextToken;

        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,200);
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
