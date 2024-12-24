<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Product;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $customer_favorites=Customer::where('user_id',$user->id)->first()->favorites;
        $products =$customer_favorites->map(function ($product)
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
                'favorites'=>$products
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(string $id)
    {
        $product_id=$id;
        $user=Auth::user();
        $customer_id=Customer::where('user_id',$user->id)->first()->id;
        $exist=DB::table('favorites')->where([
            ['product_id',$product_id],
            ['customer_id',$customer_id]
        ])->exists();
        
        if($exist){
            return response()->json([
            'Status' => 'Success',
            'message' => 'Product is already in your favorites'], 200);
        }
        DB::table('favorites')->insert([
            'product_id'=>$product_id,
            'customer_id'=>$customer_id
        ]);
        return response()->json(['Status' => 'Success','message' => 'Product added to favorites successfully'],200);        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
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
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $product =Product::where('id',$id)->first();  
    
        if (!$product) {  
            return response([  
                'Status'=> 'Failed',  
                'Error' => 'Please provide a product.'  
            ], 404); 
        } 

        $customer->favorites()->detach($product->id);  

        return response([  
            'Status' => 'Success',  
            'Message' => 'product has been deleted from favorites.'  
        ], 201);
    }
}
