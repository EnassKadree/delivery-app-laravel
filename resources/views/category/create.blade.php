@extends('admin.dashboard')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">Add Category</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('admin.category.indexweb') }}">All Categories</a>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-3 col-md-6">
                <form class="yourform" action="{{ route('admin.category.store') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Category Name (English)</label>
        <input type="text" class="form-control @error('name.en') is-invalid @enderror"
            placeholder="Category Name in English" name="name[en]" value="{{ old('name.en') }}" required>
        @error('name.en')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label>Category Name (Arabic)</label>
        <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
            placeholder="Category Name in Arabic" name="name[ar]" value="{{ old('name.ar') }}" required>
        @error('name.ar')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" id="image" name="image" class="form-control" accept="image/*">
    </div>
    <input type="submit" name="save" class="btn btn-danger" value="Save">
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
