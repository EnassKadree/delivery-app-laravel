<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(SetLocale::class);
    }

    public function index()
    {
        $locale =app()->getLocale();
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

        $status = $locale == 'ar' ? ' تم بنجاح' : 'Success';
        $message = $locale == 'ar' ? 'تم جلب البيانات بنجاح.' : 'Data has been fetched successfully.';

        return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'categories'=>$categories
            ]);
    }

    public function show(string $id)
    {
        $locale =app()->getLocale();

        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $category=Category::where('id',$id)->first();
        if(!$category)
        {

            $status = $locale == 'ar' ? 'فشل' : 'Failed';
            $message = $locale == 'ar' ? 'لا توجد فئة كهذه.' : 'There is no such  category.';

            return  response()->json(
            [
                'status'=>$status,
                'message'=>$message,
            ],400);
        }
        $products =$category->products->map(function ($product)use($customer_cart,$customer)
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
        $locale =app()->getLocale();
        $user=Auth::user();
        $customer=Customer::where('user_id',$user->id)->first();
        $customer_cart=$customer->cart;
        $word = $request->input('q');
        $category=Category::where('id',$id)->first();
        if (!$category)
        {

            $status = $locale == 'ar' ? 'خطأ' : 'Error';
            $message = $locale == 'ar' ? 'المتجر غير موجود.' : 'Store not found.';

            return response()->json([
                'status'=>$status,
                'message'=>$message,
            ], 404);
        }

        $category_products =$category->products()
        ->where('name', 'LIKE', "%{$word}%")
        ->orWhere('description', 'LIKE', "%{$word}%")
        ->get();

            $translatedProducts= $category_products ->map(function ($product)use($customer_cart,$customer)
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
            $message = $locale == 'ar' ? 'نتيجة البحث في هذه الفئة.' : 'search result in this category';

        return response()->json(
            [
                'status'=>$status,
                'message'=>$message,
                'products' => $translatedProducts,
            ],200);
    }


    public function indexweb()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.category.indexweb')->with('success', 'Category created successfully.');
    }

    public function edit(category $category)
    {
        $category->name = json_decode($category->name, true);
        return view('category.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validated();
        $category->name = json_encode($data['name']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->image = $path;
        }
        $category->save();

        return redirect()->route('admin.category.indexweb')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
        }

        return redirect()->route('admin.category.indexweb')->with('success', 'Category deleted successfully.');
    }

}
