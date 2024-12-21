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
         $categories =Category::all()->map(function ($category) use ($locale)
         {
            return
            [
            'id'=>$category->id,
            'name'=>$category->getTranslation('name',$locale),
            'image'=>$category->image
            ];
         }
        );
        return  response()->json($categories);
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
        $products =$category->products->map(function ($product) use ($locale)
        {
           return
           [
               'id' => $product->id,
               'name' => $product->getTranslation('name', $locale),
               'description' => $product->getTranslation('description', $locale),
               'price'=>$product->price,
               'stock'=>$product->stock,
               'image' => $product->image,
           ];
        }
       );

       return response()->json($products,200);
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
