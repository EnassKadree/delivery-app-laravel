<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locale = app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $orders =$customer->orders->map(function ($order)
            {
                return
                [
                'id'=>$order->id,
                'status' => $order->status,
                'address'=>$order->address,
                'total_price'=>$order->total_price
                ];
            }
            );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
            'orders'=>$orders
        ]);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function checkOrder()
    {
        $locale =app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();

        $customer_cart=$customer->cart;
        $cart_items=$customer_cart->products;


        foreach ($cart_items as $product)
        {
            $row = DB::table('cart_item')
            ->where('cart_id',$customer_cart->id )
            ->where('product_id',$product->id )
            ->first();

            if ($product->stock<$row->quantity)
            {
                $status = $locale == 'ar' ? 'فشل' : 'Failed';
                $message = $locale == 'ar'
                ? $row->quantity . ":ولكنك طلبت" . $product->stock . ":الكمية المتاحة." . $product->name . ":لا يوجد كمية كافية من هذا المنتج" 
                : "Not enough stock for the product: " . $product->name . ".Available stock is " . $product->stock . ",  but you requested " . $row->quantity . ".";
                return response()->json(
                [
                    'status'=>$status,
                    'message'=>$message,
                ],404
            );
            }
        }


        $status = $locale == 'ar' ? 'نجح' : 'Success';
        $message = $locale == 'ar' ? 'تم التحقق من الطلب ' : 'the Order checked successfully';
        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
            'customer_address'=>$customer->address
        ],200
        );

    }
    public function order(Request $request)
    {
        $locale =app()->getLocale();

        $address=$request->validate(['address'=>['required']]);

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $cart_items=$customer_cart->products;
        if($cart_items->isEmpty())
        {
            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'لا يوجد عناصر في السلة' : 'There is no items in the cart';

            return response()->json(
            [
                'status' => $status,
                'message' => $message,

            ], 403);
        }

        //create the order
        $order=Order::create([
            'total_price'=>0,
            'address'=>$address['address'],
            'customer_id'=>$customer->id,
            'status'=>'pending'
        ]);



        //move the products from cart_item to order_item

        foreach ($cart_items as $product)
        {
            //reaching the row of this product
            $row = DB::table('cart_item')
            ->where('cart_id',$customer_cart->id )
            ->where('product_id',$product->id )
            ->first();

            //inserting the product in the order
            DB::table('order_item')->insert([
            'order_id'=>$order->id,
            'product_id'=>$product->id,
            'quantity'=>$row->quantity
            ]);

            //editing the total priceof the order
            $price=($product->price)*($row->quantity);
            $order->total_price+=$price;
            $order->save();

            //editing the stock
            $product->stock= $product->stock-$row->quantity;
            $product->save();

            //deleting items from cart
            $customer_cart->products()->detach($product->id);
            $customer_cart->total_price=0;
            $customer_cart->save();
        }


            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'اكتمل الطلب بنجاح' : 'Order Completed  successfully.';

            return response()->json(
            [
                'status' => $status,
                'message' => $message,

            ], 200);
    }


    public function show($id)
    {
        $locale = app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();

        $order = $customer->orders()->where('id', $id)->first();

        if (!$order)
        {
            return response()->json(['message' => 'Order not found for this customer'], 404);
        }

        // Return the order details
        return response()->json(['order' => $order], 200);
        $products =$order->products->map(function ($product)use($order)
        {
            $row = DB::table('order_item')
            ->where('order_id',$order->id )
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
                'image' => $product->image,
                'quantity'=>$row->quantity
            ];
        }

    );
    $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
    $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';
    $order_detailes=[
        'order_status'=>$order->status,
        'total_price'=>$order->total_price,
        'created_at'=>$order->created_at,
        'address'=>$order->address
    ];
    return response()->json(
        [
            'status' => $status,
            'message' => $message,
            'products'=>$products,
            'order_detailes'=>$order_detailes
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $locale =app()->getLocale();

        $attributes= $request->validate([
            'address'=>['required'],
            'products'=>['required'],
        ]);

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $order = $customer->orders()->where('id', $id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'Order not found for this customer'
            ], 404);
        } 
        if ($order->status != 'pending') {
            return response()->json([
                'message' => "Order can't be edited"
            ], 403);
        }
        $order->address=$attributes['address'];
        $order->save();
        $products=$attributes['products'];

        if(!empty($products))
        {
            foreach($products as $listItem)
            {
                $productId = $listItem['product_id'];
                $newQuantity = $listItem['quantity'];
                $product=Product::where('id',$productId)->first();

                $row = DB::table('order_item')
                ->where('order_id',$order->id )
                ->where('product_id',$productId )
                ->first();
                $oldQuantity=$row->quantity;

                if ($newQuantity==0)
                {

                    $product->stock=$product->stock + $oldQuantity;
                    $product->save();


                    $order->total_price=$order->total_price - ($oldQuantity*$product->price);
                    $order->save();

                    $order->products()->detach($product->id);

                    $order_products=$order->products;
                    if($order_products->isEmpty())
                    {

                        Order::destroy($order->id);

                        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
                        $message = $locale == 'ar' ? 'تم حذف الطلب بنجاح.' : 'order has been deleted successfully.';
                
                        return response()->json(
                            [
                                'status' => $status,
                                'message' => $message,
                            ], 200); 
                    }
                    
                }
                else if($oldQuantity<$newQuantity)
                {
                    $diffrence=$newQuantity-$oldQuantity;

                    if($product->stock < $diffrence)
                    {
                        $status = $locale == 'ar' ? 'فشل' : 'Failed';
                        $message = $locale == 'ar'
                        ? $row->$diffrence . ":ولكنك طلبت" . $product->stock . ":الكمية المتاحة." . $product->name . ":لا يوجد كمية كافية من هذا المنتج" 
                        : "Not enough stock for the product: " . $product->name . ".Available stock is " . $product->stock . ",  but you requested " . $row->$diffrence . ".";
                        return response()->json(
                        [
                        'status'=>$status,
                        'message'=>$message,
                        ],404
                    );
                    }
                    DB::table('order_item')
                    ->where('order_id', $order->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $newQuantity]);

                    $product->stock= $product->stock-$diffrence;
                    $product->save();

                    $order->total_price=$order->total_price + ($diffrence*$product->price);
                    $order->save();
                }
                else if($oldQuantity>$newQuantity)
                {
                    $diffrence=$oldQuantity-$newQuantity;

                    $product->stock=$product->stock + $diffrence;
                    $product->save();

                    DB::table('order_item')
                    ->where('order_id', $order->id)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $newQuantity]);

                    $order->total_price=$order->total_price - ($diffrence*$product->price);
                    $order->save();
                    
                }
                
        }
    }
        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم التعديل بنجاح.' : 'Data has been updated successfully.';

        return response()->json(
            [
                'status' => $status,
                'message' => $message,
            ], 200); 

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $locale =app()->getLocale();
        $user=Auth::user();

        $customer=Customer::where('user_id',$user->id)->first();
        $order = $customer->orders()->where('id', $id)->first();
        if (!$order) {
            return response()->json([
                'message' => 'Order not found for this customer'
            ], 404);
        }
        $order_items=$order->products;
        foreach($order_items  as $product)
        {
        $row = DB::table('order_item')
        ->where('order_id',$order->id )
        ->where('product_id',$product->id )
        ->first();

        $product->stock= $product->stock+$row->quantity;
        $product->save();

        }
        Order::destroy($id);

    $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
    $message = $locale == 'ar' ? 'تم حذف الطلب بنجاح' : 'order deleeted  successfully.';
    return response()->json(
        [
            'status' => $status,
            'message' => $message
        ], 200);

    }
}
