<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Collection;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(SetLocale::class);
    }

    public function index()
    {

        $locale = app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $products =Product::latest()->take(20)->get()->map(function ($product)use($customer_cart,$customer)
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
            'status' => $status,
            'message' => $message,
            'products'=> $products
        ]
        );

    }


    public function show(string $id)
    {
        $locale =app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;

        $product=Product::where('id',$id)->first();

        if ($product)
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

            $translatedProduct = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price'=>$product->price,
                'category'=>$product->category->name,
                'stock'=>$product->stock,
                'store'=>$product->store->name,
                'store_image'=>$product->store->logo_image,
                'image' => $product->image,
                'isInCart'=>$isInCart,
                'isFavorite'=>$isFavorite
            ];

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
            $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

            return response()->json(
            [
                'status' => $status,
                'message' => $message,
                'product'=>$translatedProduct,
            ], 200);
        }

        $status = $locale == 'ar' ? 'فشل' : 'Failed';
        $message = $locale == 'ar' ? 'المنتج غير موجود' : 'Product not found.';

        return response()->json([
            'status' => $status,
            'message' => $message,
        ], 404);
    }


    public function search(Request $request)
    {
        $locale =app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $word = $request->input('q');

        $products = Product::where('name', 'LIKE', "%{$word}%")
            ->orWhere('description', 'LIKE', "%{$word}%")
            ->get();

            $translatedProducts=$products->map(function ($product)use($customer_cart,$customer)
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
                    'price'=>$product->price,
                    'category'=>$product->category->name,
                    'stock'=>$product->stock,
                    'store'=>$product->store->name,
                    'store_image'=>$product->store->logo_image,
                    'image' => $product->image,
                    'isInCart'=>$isInCart,
                    'isFavorite'=>$isFavorite
                ];
                }
            );

        $stores = Store::where('name', 'LIKE', "%{$word}%")
            ->get();
            $translatedStores =$stores->map(function ($store)
            {
                return
                [
                'id'=>$store->id,
                'name'=>$store->name,
                'address'=>$store->address,
                'image'=>$store->logo_image
                ];
            }
            );

            $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';

        return response()->json(
            [
                'status' => $status,
            'products' => $translatedProducts,
            'stores' => $translatedStores,
            ],200);
    }


        public function indexweb()
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }


        public function create()
    {
        $categories = Category::all();
        $stores = Store::all();
        return view('product.create', compact('categories', 'stores'));
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-images', 'public');
        } else {
            $data['image'] = null;
        }
        Product::create($data);

        return redirect()->route('admin.product.indexweb')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->name = json_decode($product->name, true);
        $product->description = json_decode($product->description, true);

        $categories = Category::all();
        $stores = Store::all();

        return view('product.edit', [
            'product' => $product,
            'categories' => $categories,
            'stores' => $stores,
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validated();

        $product->name = json_encode($request->input('name'));
        $product->description = json_encode($request->input('description'));
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->category_id = $request->input('category_id');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product-images', 'public');
            $product->image = $path;
        }

        $product->save();

        return redirect()->route('admin.product.indexweb')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
        }

        return redirect()->route('admin.product.indexweb')->with('success', 'Product deleted successfully.');
    }


}
