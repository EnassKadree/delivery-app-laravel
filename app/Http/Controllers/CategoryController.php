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
        return  response()->json(
            [
            'Status' => 'Success',
            'Message' => 'Data has been fetched successfuly.',
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

        $category=Category::where('id',$id)->first();
        if(!$category)
        {
        return  response()->json(
        [
            'Status' => 'Failed',
            'Message' => 'There is no such  category.',
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

    return response()->json(
        [
        'Status' => 'Success',
        'Message' => 'Data has been fetched successfuly.',
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
}
