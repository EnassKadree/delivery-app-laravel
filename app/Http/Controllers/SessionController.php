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

        $locale = app()->getLocale();
        //validate
        $attributes=request()->validate([
            'phone'=>['required'],
            'password'=>['required'],
            'email'=>['required']
        ]);
        $user = User::where('phone', $attributes['phone'])->first();
        if (!$user) {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'الرقم المدخل خاطئ': 'The provided phone number is incorrect.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 401);
        }

        if ($user->email !== $attributes['email']) {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'البريد الإلكتروني المعطى لا يتطابق مع رقم الهاتف ': 'The provided email does not match the phone number.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 401);
        }

        if (!Hash::check($attributes['password'], $user->password)) {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'كلمة المرور المدخلة خاطئة': 'The provided password is incorrect.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 401);
        }

        // attempt to login the user

        $phone=$request['phone'];
        $user=User::where('phone',$phone)->first();
        $token = $user->createToken('usertoken')->plainTextToken;

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        $response=[
            'status'=>$status,
            'message'=>$message,
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
        $locale = app()->getLocale();

        $user = User::find(Auth::id());

        $customer=Customer::where('user_id',$user->id)->first();

        $attributes=request()->validate([
            'first_name'=>['required'],
            'last_name'=>['required'],
            'address'=>['required'],
            'phone'=>['required'],
        ]);

        $user->update(
            [
                'phone' => $attributes['phone']
            ]
            );

            $customer->update([
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'address' => $attributes['address'],
        ]);

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم نعديل البيانات بنجاح.' : 'updated successfully ';

        return response([
            'status' => $status,
            'message' => $message,
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

public function storeWeb(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'phone' => 'required',
    ]);

        $credentials = $request->only('email', 'password', 'phone');

    if (Auth::attempt($credentials)) {
        $user = User::find(Auth::id());
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('login');
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
