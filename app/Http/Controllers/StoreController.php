<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;


class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware(SetLocale::class);
    }

    public function index()
    {
        $locale = app()->getLocale();

        $stores =Store::all()->map(function ($store)
            {
                return
                [
                'id'=>$store->id,
                'name' => $store->name,
                'address'=>$store->address,
                'image'=>$store->logo_image
                ];
            }
            );

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
        [
            'status'=>$status,
            'message'=>$message,
            'stores'=>$stores
        ]);
    }


    public function show(string $id)
    {

        $locale = app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $store=Store::where('id',$id)->first();

        if(!$store)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'هذا المتجر غير موجود' : 'There is no such  store.';

            return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            ],400);
        }

        $products =$store->products->map(function ($product)use($customer_cart,$customer)
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


        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            'products'=>$products
            ],200);
    }


    public function search(Request $request, $id)
    {

        $locale = app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $word = $request->input('q');

        $store=Store::where('id',$id)->first();
        if (!$store)
        {

            $status = $locale == 'ar' ? 'خطأ' : 'Error';
            $message = $locale == 'ar' ? 'المتجر غير موجود' : 'Store not found.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 404);
        }

        $store_products =$store->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();

            $translatedProducts= $store_products ->map(function ($product)use($customer_cart,$customer)
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

        $status = $locale == 'ar' ? 'تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'نتيجة البحث في هذا المتجر ' : 'search result in this store.';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'products' => $translatedProducts,
            ],200);
    }

    public function indexweb()
{
    $stores = Store::all();
    return view('store.index', compact('stores'));
}


    public function create()
    {
        return view('store.create');
    }

    public function store(StoreStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo_image')) {
            $data['logo_image'] = $request->file('logo_image')->store('logos', 'public');
        } else {
            $data['logo_image'] = null;
        }

        Store::create($data);

        return redirect()->route('admin.store.indexweb')->with('success', 'Store created successfully.');
    }

    public function edit(store $store)
    {
        $store->name = json_decode($store->name, true);
        $store->address = json_decode($store->address, true);
        return view('store.edit', [ 'store' => $store ]);
    }

    public function update(UpdateStoreRequest $request, $id)
    {
        $store = Store::findOrFail($id);
        $data = $request->validated();
        $store->name = json_encode($request->input('name'));
        $store->address = json_encode($request->input('address'));

        if ($request->hasFile('logo_image')) {
            $path = $request->file('logo_image')->store('logos', 'public');
            $store->logo_image = $path;
        }

        $store->save();

        return redirect()->route('admin.store.indexweb')->with('success', 'Store updated successfully.');
    }

    public function destroy($id)
    {
        $store = Store::find($id);

        if ($store) {
            $store->delete();
        }

        return redirect()->route('admin.store.indexweb');
    }
}
