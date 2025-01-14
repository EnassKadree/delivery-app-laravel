<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Cart;
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
        $locale = app()->getLocale();
        //validate
        $attributes=$request->validate([
            'fcm_token'=>['required'],
            'first_name'=>['required'],
            'last_name'=>['required'],
            'phone' => ['required','regex:/^09\d{8}$/','size:10'],
            'email'=>['required','email'],
            'password'=>['required','confirmed'],
            'address'=>['required'],
        ]);

        $existingUser = User::where('phone', $attributes['phone'])->first();

        $status = $locale == 'ar' ? 'فشل' : 'Failed';
        $message = $locale == 'ar' ? 'رقم الهاتف موجود بالفعل' : 'Phone number already exists.';

        if ($existingUser) {
            return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
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
            'fcm_token'=>$attributes['fcm_token'],
            'user_id'=>$user['id'],
            'first_name'=>$attributes['first_name'],
            'last_name'=>$attributes['last_name'],
            'image'=>$request['image'],
            'address'=>$attributes['address'],
        ]);

        Cart::create([
            'customer_id'=>$customer->id,
            'total_price'=>0
        ]);

        $token=$user->createToken('usertoken')->plainTextToken;
        //log in
        Auth::login($customer);

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم التسجيل بنجاح! تم إرسال بريد إلكتروني للتأكيد.' : 'Registration successful! A verification email has been sent.';

        $response=[
            'status'=>$status,
            'message'=>$message,
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
        $locale = app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json([
            'status'=>$status,
            'message'=>$message,
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
