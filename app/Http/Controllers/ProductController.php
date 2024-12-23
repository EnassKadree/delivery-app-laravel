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
                'name' => $product->name,
                'description' => $product->description,
                'category'=>$product->category->name,
                'price'=>$product->price,
                'store'=>$product->store->name,
                'stock'=>$product->stock,
                'image' => $product->image,
            ];
        }
        );
        return response()->json(
           [
             'Status' => 'Success',
            'Message' => 'Data has been fetched successfuly.',
           'products'=> $products
           ]
        );

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
                'name' => $product->name,
                'description' => $product->description,
                'price'=>$product->price,
                'category'=>$product->category->name,
                'stock'=>$product->stock,
                'store'=>$product->store->name,
                'image' => $product->image,
            ];
            return response()->json(
            [
                'Status' => 'Success',
               'Message' => 'Data has been fetched successfuly.',
               'product'=>$translatedProduct
            ], 200);
        }
        return response()->json([
            'Status' => 'Failed',
            'message' => 'Product not found'
        ], 404);
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
                'name' => $product->name,
                'description' => $product->description,
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
            'name'=>$store->name,
            'address'=>$store->address,
            'image'=>$store->image
            ];
        }
        );
    return response()->json(
        [
        'Status' => 'Success',
        'products' => $translatedProducts,
        'stores' => $translatedStores,
        ],200);
}
}
