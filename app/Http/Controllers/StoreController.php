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
        return response()->json(
        [
        'Status' => 'Success',
        'Message' => 'Data has been fetched successfuly.',
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

        $store=Store::where('id',$id)->first();

        if(!$store)
        {
            return  response()->json(
            [
                'Status' => 'Failed',
                'Message' => 'There is no such  store.',
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
