<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Middleware\SetLocale;


class ProductController extends Controller
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

        $products =Product::latest()->take(20)->get()->map(function ($product) use ($locale)
        {
            return
            [
                'id' => $product->id,
                'name' => $product->getTranslation('name', $locale),
                'description' => $product->getTranslation('description', $locale),
                'category'=>$product->category->name,
                'price'=>$product->price,
                'store'=>$product->store->name,
                'stock'=>$product->stock,
                'image' => $product->image,
            ];
        }
        );
        return response()->json($products);

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

        $product=Product::where('id',$id)->first();

        if ($product)
        {
            $translatedProduct = [
                'id' => $product->id,
                'name' => $product->getTranslation('name', $locale),
                'description' => $product->getTranslation('description', $locale),
                'price'=>$product->price,
                'category'=>$product->category->name,
                'stock'=>$product->stock,
                'store'=>$product->store->name,
                'image' => $product->image,
            ];
            return response()->json($translatedProduct, 200);
        }
        return response()->json(['message' => 'Product not found'], 404);
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
    public function search(Request $request)
{
    $locale =app()->getLocale();
    $word = $request->input('q'); 

    $products = Product::where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->with('store')
        ->get();
        $translatedProducts=$products->map(function ($product) use ($locale)
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

    $stores = Store::where('name', 'LIKE', "%{$word}%")
        ->get();
        $translatedStores =$stores->map(function ($store) use ($locale)
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
    return response()->json([
        'products' => $translatedProducts,
        'stores' => $stores,
    ],200);
}
}
