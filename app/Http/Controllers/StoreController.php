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

        $locale =app()->getLocale();
        $stores =Store::all()->map(function ($store) use ($locale)
            {
                return
                [
                'id'=>$store->id,
                'name'=>$store->getTranslation('name',$locale),
                'address'=>$store->getTranslation('address',$locale),
                'image'=>$store->image
                ];
            }
            );
        return response()->json($stores);
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
        $locale =app()->getLocale();
        $store=Store::where('id',$id)->first();
        $products =$store->products->map(function ($product) use ($locale)
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
