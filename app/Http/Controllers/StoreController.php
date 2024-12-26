<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware(SetLocale::class);
    }

    public function index()
    {
        $locale = app()->getLocale();

        $stores =Store::all()->map(function ($store)
            {
                return
                [
                'id'=>$store->id,
                'name' => $store->name,
                'address'=>$store->address,
                'image'=>$store->logo_image
                ];
            }
            );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
            'stores'=>$stores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $locale = app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $store=Store::where('id',$id)->first();

        if(!$store)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'هذا المتجر غير موجود' : 'There is no such  store.';

            return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            ],400);
        }

        $products =$store->products->map(function ($product)use($customer_cart,$customer)
        {

            if( !$product->carts )
            {
                $isInCart=false;
            }
            else
            {
                $isInCart = $product->carts->pluck('id')->contains($customer_cart->id);
            }

            if(!$product->customers)
            {
                $isFavorite=false;
            }
            else
            {
                $isFavorite=$product->customers->pluck('id')->contains($customer->id);
            }

            return
            [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category'=>$product->category->name,
                'price'=>$product->price,
                'store'=>$product->store->name,
                'store_image'=>$product->store->logo_image,
                'stock'=>$product->stock,
                'image' => $product->image,
                'isInCart'=>$isInCart,
                'isFavorite'=>$isFavorite
            ];
        }
    );


        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            'products'=>$products
            ],200);
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

    public function search(Request $request, $id)
    {

        $locale = app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $word = $request->input('q');

        $store=Store::where('id',$id)->first();
        if (!$store)
        {

            $status = $locale == 'ar' ? 'خطأ' : 'Error';
            $message = $locale == 'ar' ? 'المتجر غير موجود' : 'Store not found.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 404);
        }

        $store_products =$store->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();

            $translatedProducts= $store_products ->map(function ($product)use($customer_cart,$customer)
            {

                if( !$product->carts )
                {
                    $isInCart=false;
                }
                else
                {
                    $isInCart = $product->carts->pluck('id')->contains($customer_cart->id);
                }

                if(!$product->customers)
                {
                    $isFavorite=false;
                }
                else
                {
                    $isFavorite=$product->customers->pluck('id')->contains($customer->id);
                }

                return
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'category'=>$product->category->name,
                    'price'=>$product->price,
                    'store'=>$product->store->name,
                    'store_image'=>$product->store->logo_image,
                    'stock'=>$product->stock,
                    'image' => $product->image,
                    'isInCart'=>$isInCart,
                    'isFavorite'=>$isFavorite
                ];
            }
        );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'نتيجة البحث في هذا المتجر ' : 'search result in this store.';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'products' => $translatedProducts,
            ],200);
    }
}


