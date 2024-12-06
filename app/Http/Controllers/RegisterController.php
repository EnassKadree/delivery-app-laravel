<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

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
            'password'=>['required','confirmed'],
            'address'=>['required'],
        ]);
        //create user
        $user=User::create([
            'phone'=>$attributes['phone'],
            'password'=>bcrypt($attributes['password']),
            'role'=>'customer',
        ]);
        $token=$user->createToken('usertoken')->plainTextToken;
        //create customer
        $customer=Customer::create([
            'user_id'=>$user['id'],
            'first_name'=>$attributes['first_name'],
            'last_name'=>$attributes['last_name'],
            'image'=>$request['image'],
            'address'=>$attributes['address'],
        ]);
        //log in
        Auth::login($customer);
        $response=[
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
