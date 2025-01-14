@extends('admin.dashboard')
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div id="admin-content">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h2 class="admin-heading">Update Product</h2>
            </div>
            <div class="offset-md-7 col-md-2">
                <a class="add-new" href="{{ route('admin.product.indexweb') }}">All Products</a>
            </div>
        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <form class="yourform" action="{{ route('admin.product.update', $product->id) }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Product Name -->
                    <div class="form-group">
                        <label>Product Name (English)</label>
                        <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                               placeholder="Product Name in English" name="name[en]" value="{{ old('name.en', $product->getTranslation('name', 'en') ?? '') }}" required>
                        @error('name.en')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Product Name (Arabic)</label>
                        <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
                               placeholder="Product Name in Arabic" name="name[ar]" value="{{ old('name.ar', $product->getTranslation('name', 'ar') ?? '') }}" required>
                        @error('name.ar')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Product Description -->
                    <div class="form-group">
                        <label>Product Description (English)</label>
                        <textarea class="form-control @error('description.en') is-invalid @enderror"
                                  placeholder="Product Description in English" name="description[en]" required>{{ old('description.en', $product->getTranslation('description', 'en') ?? '') }}</textarea>
                        @error('description.en')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Product Description (Arabic)</label>
                        <textarea class="form-control @error('description.ar') is-invalid @enderror"
                                  placeholder="Product Description in Arabic" name="description[ar]" required>{{ old('description.ar', $product->getTranslation('description', 'ar') ?? '') }}</textarea>
                        @error('description.ar')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                               placeholder="Product Price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                        @error('price')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                               placeholder="Stock Quantity" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category Dropdown -->
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                            <option value="">Select a Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Store Dropdown -->
                    <div class="form-group">
                        <label for="store_id">Store</label>
                        <select class="form-control @error('store_id') is-invalid @enderror" name="store_id" id="store_id" required>
                            <option value="">Select a Store</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ old('store_id', $product->store_id) == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('store_id')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label for="image">Product Image</label>
                        <div class="mb-2">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail" width="100">
                            @endif
                        </div>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        @error('image')
                            <div class="alert alert-danger" role="alert">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="submit" name="save" class="btn btn-danger" value="Update">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="module">
    import { handleImageInput } from '/js/pintura-handler.js';
    handleImageInput('#image');
</script>
@endsection
