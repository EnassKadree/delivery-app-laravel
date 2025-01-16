<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Services\FcmService;

use Illuminate\Http\Request;

class CartController extends Controller
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

    }

    /**
     * Store a newly created resource in storage.
     */
    public function addToCart(Request $request,$id)
    {
        $locale =app()->getLocale();

        $product=Product::where('id',$id)->first();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $stock=$product->stock;

        //checking if product is available
        if ($stock==0 )
        {

            $status = $locale == 'ar' ? ' فشل' : 'Failed';
            $message = $locale == 'ar' ? 'نفدت الكمية.' : 'out of stock.';

            return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
                ], 400);
        }


        //if the cart_item row exist
        $exist=DB::table('cart_item')->where([
            ['product_id',$product->id],
            ['cart_id',$customer_cart->id]
        ])->exists();


        if (!$exist)
        {
            DB::table('cart_item')->insert([
                'product_id'=>$product->id,
                'cart_id'=>$customer_cart->id,
                'quantity'=>1
                ]);

                // update the total price
            $updatedPrice=$customer_cart->total_price+$product->price;

            $customer_cart->update(
                ['total_price' => $updatedPrice]
            );

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'تم إضافة المنتج إلى السلة بنجاح.' : 'added to cart successfully.';

            return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
                ], 201);

        }
      //checking if the product in the cart to increase the quantity
        if ($exist)
        {
            $row = DB::table('cart_item')
            ->where('cart_id',$customer_cart->id )
            ->where('product_id',$product->id )
            ->first();

            if ($stock == $row->quantity)
            {

                $status = $locale == 'ar' ? ' فشل' : 'Failed';
                $message = $locale == 'ar' ? 'نفدت الكمية.' : 'out of stock.';

                return response()->json(
                    [
                        'status'=>$status,
                        'message'=>$message,
                    ], 400);
            }

            DB::table('cart_item')
                ->where('cart_id', $customer_cart->id)
                ->where('product_id', $product->id)
                ->update([
                    'quantity' =>$row->quantity + 1,
                ]);


                $updatedPrice=$customer_cart->total_price+$product->price;
                $customer_cart->update(
                    ['total_price' => $updatedPrice]
                );

                $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
                $message = $locale == 'ar' ? 'تم إضافة المنتج إلى السلة بنجاح.' : 'added to cart successfully.';

            return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
                'quantity'=> $row->quantity+1
                ], 200);

        }



    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $locale =app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $cart_items=$customer_cart->products;

        $products =$cart_items->map(function ($product)use($customer_cart,$customer)
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

            $row = DB::table('cart_item')
            ->where('cart_id',$customer_cart->id )
            ->where('product_id',$product->id )
            ->first();
            return
            [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category'=>$product->category->name,
                'price'=>$product->price,
                'store'=>$product->store->name,
                'store_image'=>$product->store->logo_image,
                'stock'=>$product->stock,
                'quantity'=>$row->quantity,
                'image' => $product->image,
                'isInCart'=>$isInCart,
                'isFavorite'=>$isFavorite
            ];
        }
        );



        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'المنتج في هذه السلة' : 'product in this cart.';

        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
            'products'=> $products,
            'total_price'=>$customer->cart->total_price
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */


     public function destroy($id)
     {
         $locale =app()->getLocale();

         $fcms=new FcmService();
         $product=Product::where('id',$id)->first();
         $user=Auth::user();
         $customer=Customer::where('user_id',$user->id)->first();
         $customer_cart=$customer->cart;

         $row = DB::table('cart_item')
         ->where('cart_id',$customer_cart->id )
         ->where('product_id',$product->id )
         ->first();

         if (!$row)
             {
                 $status = $locale == 'ar' ? ' فشل' : 'Failed';
                 $message = $locale == 'ar' ? 'غير موجود': 'does not exist.';
                 return response()->json(
                 [
                     'status'=>$status,
                     'message'=>$message,
                 ], 400);
             }


         $total=($row->quantity)*($product->price);
         $updatedPrice=$customer_cart->total_price-$total;
                 $customer_cart->update(
                     [
                         'total_price' => $updatedPrice
                     ]
                 );

         $customer_cart->products()->detach($product->id);
         $title="Product deleted from the cart";
                 $body="doneeeeeeeeeeee!";

                 $fcms->sendNotification($customer->fcm_token,$title,$body);

         $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
         $message = $locale == 'ar' ? 'تم حذف المنتج من السلة.' : 'product has been deleted from cart.';

         return response([
             'status'=>$status,
             'message'=>$message,
         ], 200);
     }





    public function removeOneItem($id)
    {
        $locale =app()->getLocale();

        $product=Product::where('id',$id)->first();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;

        $row = DB::table('cart_item')
            ->where('cart_id',$customer_cart->id )
            ->where('product_id',$product->id )
            ->first();

            if (!$row)
            {

                $status = $locale == 'ar' ? ' فشل' : 'Failed';
                $message = $locale == 'ar' ? 'غير موجود': 'does not exist.';

            return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
                ], 400);
            }


            $updatedPrice=$customer_cart->total_price-($product->price);
                    $customer_cart->update(
                        [
                            'total_price' => $updatedPrice
                        ]
                    );

            if ($row->quantity>1)
            {
                DB::table('cart_item')
                ->where('cart_id', $customer_cart->id)
                ->where('product_id', $product->id)
                ->update([
                    'quantity' =>$row->quantity - 1,
                ]);

                $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
                $message = $locale == 'ar' ? 'تم حذف المنتج ' : 'product is removed.';

                return response()->json(
                    [
                        'status'=>$status,
                        'message'=>$message,
                        'quantity'=> $row->quantity-1
                    ], 200);

            }
            $customer_cart->products()->detach($product->id);

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'تم حذف المنتج من السلة.' : 'product has been deleted from cart.';

            return response([
                'status'=>$status,
                'message'=>$message,
            ], 200);
    }



    public function search(Request $request)
    {
        $locale =app()->getLocale();

        $word = $request->input('q');
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $cart_products = $customer_cart->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();


            $translatedProducts= $cart_products->map(function ($product)use($customer_cart,$customer)
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
                    'store_image'=>$product->store->logo_image,
                    'stock'=>$product->stock,
                    'image' => $product->image,
                    'isInCart'=>$isInCart,
                    'isFavorite'=>$isFavorite
                ];
            }
            );

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'نتائج البحث في هذه السلة.' : 'search result in this cart';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'products' => $translatedProducts,
            ],200);
    }
}
