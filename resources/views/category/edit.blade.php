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
                <h2 class="admin-heading">Update Category</h2>
            </div>
        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <form class="yourform" action="{{ route('admin.category.update', $category->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Category Name (English)</label>
                        <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                        placeholder="Category Name in English" name="name[en]" value="{{ old('name.en', $category->getTranslation('name', 'en')) }}">


                        @error('name.en')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Category Name (Arabic)</label>
                        <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
                            placeholder="Category Name in Arabic" name="name[ar]" value="{{ old('name.ar', $category->getTranslation('name', 'ar')) }}">
                        @error('name.ar')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image">Category Image</label>
                        <div class="mb-2">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail" width="100">
                            @endif
                        </div>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    </div>
                    <input type="submit" name="save" class="btn btn-success" value="Update">
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
