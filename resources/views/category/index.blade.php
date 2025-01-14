@extends('admin.dashboard')
@section('content')

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Categories</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('admin.category.create') }}">Add Category</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>Id</th>
                            <th>Category Name (English)</th>
                            <th>Category Name (Arabic)</th>
                            <th>Image</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>{{ $category['id'] }}</td>
                                    <td>{{ $category->getTranslation('name', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $category->getTranslation('name', 'ar') ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($category['image']))
                                            <a href="{{ asset('storage/' . $category['image']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $category['image']) }}" alt="{{ $category->getTranslation('name', 'en') }}" width="50" height="50">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/placeholder-image.png') }}" alt="No Image" width="50" height="50">
                                        @endif
                                    </td>
                                    <td class="edit" style="text-align: center;">
                                        <a href="{{ route('admin.category.edit', ['category' => $category['id']]) }}" class="btn btn-success">Edit</a>
                                    </td>
                                    <td class="delete" style="text-align: center;">
                                        <form action="{{ route('admin.category.destroy', ['id' => $category['id']]) }}" method="POST" class="form-hidden">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger delete-category">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No Categories Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
