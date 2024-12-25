<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
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
                'success' => 'Failed',
                'messages' => 'The provided phone number is incorrect.'
            ], 401);
        }

        if ($user->email !== $attributes['email']) {
            return response()->json([
                'success' => false,
                'messages' =>'The provided email does not match the phone number.'
            ], 401);
        }

        if (!Hash::check($attributes['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'messages' => 'The provided password is incorrect.'
            ], 401);
        }

        // attempt to login the user

        $phone=$request['phone'];
        $user=User::where('phone',$phone)->first();
        $token = $user->createToken('usertoken')->plainTextToken;

        $response=[
            'Status' => 'Success',
            'Message' => 'Data has been fetched successfuly.',
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
    public function update(Request $request)
    {
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $attributes=request()->validate([
            'first_name'=>['required'],
            'last_name'=>['required'],
            'address'=>['required'],
            'phone'=>['required'],
        ]);

        $user->User::update(
            [
                'phone' => $attributes['phone']
            ]
             );
             $customer->update([
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'address' => $attributes['address'],
        ]);
        return response([
            'Status' => 'Success',
            'Message' => 'updated successfully ',
            'customer' => [
                'first_name' =>$attributes['first_name'],
                'last_name' =>$attributes['last_name'],
                'image' =>$request->image,
                'phone' => $attributes['phone'],
                'address'=>$attributes['address']
            ],
        ], 200);


 }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
