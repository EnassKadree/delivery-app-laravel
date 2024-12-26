<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware(SetLocale::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale =app()->getLocale();
        $user=Auth::user();
        $customer_favorites=Customer::where('user_id',$user->id)->first()->favorites;
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $products =$customer_favorites->map(function ($product)use($customer_cart,$customer)
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
                'store_image'=>$product->store->image,
                'stock'=>$product->stock,
                'image' => $product->image,
                'isInCart'=>$isInCart,
                'isFavorite'=>$isFavorite
            ];
        }
    );


        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'favorites'=>$products
            ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(string $id)
    {
        $locale =app()->getLocale();
        $product_id=$id;
        $user=Auth::user();
        $customer_id=Customer::where('user_id',$user->id)->first()->id;

        $exist=DB::table('favorites')->where([
            ['product_id',$product_id],
            ['customer_id',$customer_id]
        ])->exists();

        if($exist){

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'المنتج موجود بالفعل في المفضلة لديك.' : 'Product is already in your favorites.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
        ], 200);
        }
        DB::table('favorites')->insert([
            'product_id'=>$product_id,
            'customer_id'=>$customer_id
        ]);

        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم إضافة المنتج إلى المفضلة بنجاح.' : 'Product added to favorites successfully.';

        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
        ],200);
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
        $locale =app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $product =Product::where('id',$id)->first();

        if (!$product) {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'يرجى تقديم منتج.' : 'Please provide a product.';

            return response([
                'status'=>$status,
                'message'=>$message,
            ], 404);
        }

        $exist=DB::table('favorites')->where([
            ['product_id',$product->id],
            ['customer_id',$customer->id]
        ])->exists();

        if(!$exist)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'المنتج غير موجود في المفضلة.' : 'Product does not exist in favorites.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
        ], 400);
        }

        $customer->favorites()->detach($product->id);

        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم حذف المنتج من المفضلة.' : 'product has been deleted from favorites.';

        return response([
            'status'=>$status,
            'message'=>$message,
        ], 201);
    }
}
