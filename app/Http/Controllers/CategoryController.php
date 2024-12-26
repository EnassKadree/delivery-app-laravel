<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale;
class CategoryController extends Controller
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
        $locale =app()->getLocale();
        $categories =Category::all()->map(function ($category)
        {
            return
            [
            'id'=>$category->id,
            'name'=>$category->name,
            'image'=>$category->image
            ];
        }
        );

        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';  

        return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'categories'=>$categories
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $locale =app()->getLocale();
        $category=Category::where('id',$id)->first();
        if(!$category)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'لا توجد فئة كهذه.' : 'There is no such  category.';

            return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            ],400);
        }
        $products =$category->products->map(function ($product)
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

    $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
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
        $locale =app()->getLocale();
        $word = $request->input('q');

        $category=Category::where('id',$id)->first();
        if (!$category)
        {

            $status = $locale == 'ar' ? 'خطأ' : 'Error';
            $message = $locale == 'ar' ? 'المتجر غير موجود.' : 'Store not found.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 404);
        }

        $category_products =$category->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();

            $translatedProducts= $category_products ->map(function ($product)
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

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'نتيجة البحث في هذه الفئة.' : 'search result in this category'; 

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'products' => $translatedProducts,
            ],200);
    }
}
