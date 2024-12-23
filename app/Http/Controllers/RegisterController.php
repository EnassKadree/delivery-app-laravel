<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExceptionsTwilioException;
use App\Http\Controllers\Client;

class RegisterController extends Controller
{


    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //validate
        $attributes=$request->validate([
            'first_name'=>['required'],
            'last_name'=>['required'],
            'phone' => ['required','regex:/^09\d{8}$/','size:10'],
            'email'=>['required','email'],
            'password'=>['required','confirmed'],
            'address'=>['required'],
        ]);
        $existingUser = User::where('phone', $attributes['phone'])->first();

        if ($existingUser) {
            return response()->json(
                [
                'Status' => 'Failed',
                'message' => 'Phone number already exists.'
                ], 400);
        }
        //create user
        $user=User::create([
            'phone'=>$attributes['phone'],
            'email'=>$attributes['email'],
            'password'=>bcrypt($attributes['password']),
            'role'=>'customer',
        ]);
        //create customer
        $customer=Customer::create([
            'user_id'=>$user['id'],
            'first_name'=>$attributes['first_name'],
            'last_name'=>$attributes['last_name'],
            'image'=>$request['image'],
            'address'=>$attributes['address'],
        ]);
        $token=$user->createToken('usertoken')->plainTextToken;
        //log in
        Auth::login($customer);
        $response=[
            'Status' => 'Success',
            'message' => 'Registration successful! A verification email has been sent.',
            'customer'=>$customer,
            'token'=>$token
        ];
        return response($response,201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        return response()->json([
            'Status' => 'Success',
            'Message' => 'Data has been fetched successfuly.',
            'customer' => [
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'image' => $customer->image,
                'phone' => $user->phone,
                'address'=>$customer->address,
            ],

        ]);
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
