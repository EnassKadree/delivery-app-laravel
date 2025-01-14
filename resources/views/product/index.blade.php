@extends('admin.dashboard')
@section('content')

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Products</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('admin.product.create') }}">Add Product</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="content-table">
                        <thead>
                            <th>Id</th>
                            <th>Product Name (English)</th>
                            <th>Product Name (Arabic)</th>
                            <th>Description (English)</th>
                            <th>Description (Arabic)</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Category</th>
                            <th>Store</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->getTranslation('name', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $product->getTranslation('name', 'ar') ?? 'N/A' }}</td>
                                    <td>{{ $product->getTranslation('description', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $product->getTranslation('description', 'ar') ?? 'N/A' }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>{{ $product->category->getTranslation('name', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $product->store->getTranslation('name', 'en') ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($product->image))
                                            <a href="{{ asset('storage/' . $product->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->getTranslation('name', 'en') }}" width="50" height="50">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/placeholder-image.png') }}" alt="No Image" width="50" height="50">
                                        @endif
                                    </td>
                                    <td class="edit" style="text-align: center;">
                                        <a href="{{ route('admin.product.edit', ['product' => $product->id]) }}" class="btn btn-success">Edit</a>
                                    </td>
                                    <td class="delete" style="text-align: center;">
                                    <form action="{{ route('admin.product.destroy', ['id' => $product->id]) }}" method="POST" class="form-hidden">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger delete-product">Delete</button>
                                    </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12">No Products Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
