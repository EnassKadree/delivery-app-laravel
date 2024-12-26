<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale;
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
                'image'=>$store->image
                ];
            }
            );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
        [
            $status,
            $message,
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

        $store=Store::where('id',$id)->first();

        if(!$store)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'هذا المتجر غير موجود' : 'There is no such  store.';

            return  response()->json(
            [
                $status,
                $message,
            ],400);
        }

        $products =$store->products->map(function ($product)
        {
            return
            [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->desciption,
                'price'=>$product->price,
                'stock'=>$product->stock,
                'image' => $product->image,
            ];
        }
        );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
            [
            $status,
            $message,
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

        $word = $request->input('q');

        $store=Store::where('id',$id)->first();
        if (!$store)
        {

            $status = $locale == 'ar' ? 'خطأ' : 'Error';
            $message = $locale == 'ar' ? 'المتجر غير موجود' : 'Store not found.';

            return response()->json([
                $status,
                $message,
            ], 404);
        }

        $store_products =$store->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();

            $translatedProducts= $store_products ->map(function ($product)
                {
                return
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price'=>$product->price,
                    'stock'=>$product->stock,
                    'image' => $product->image,
                ];
                }
            );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'نتيجة البحث في هذا المتجر ' : 'search result in this store.';

        return response()->json(
            [
                $status,
                $message,
                'products' => $translatedProducts,
            ],200);
    }
}


