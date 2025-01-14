@extends('admin.dashboard')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">Add Store</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('admin.store.indexweb') }}">All Stores</a>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-3 col-md-6">
                <form class="yourform" action="{{ route('admin.store.create') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Store Name (English)</label>
        <input type="text" class="form-control @error('name.en') is-invalid @enderror"
            placeholder="Store Name in English" name="name[en]" value="{{ old('name.en') }}" required>
        @error('name.en')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label>Store Name (Arabic)</label>
        <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
            placeholder="Store Name in Arabic" name="name[ar]" value="{{ old('name.ar') }}" required>
        @error('name.ar')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label>Address (English)</label>
        <input type="text" class="form-control @error('address.en') is-invalid @enderror"
            placeholder="Address in English" name="address[en]" value="{{ old('address.en') }}" required>
        @error('address.en')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="form-group">
        <label>Address (Arabic)</label>
        <input type="text" class="form-control @error('address.ar') is-invalid @enderror"
            placeholder="Address in Arabic" name="address[ar]" value="{{ old('address.ar') }}" required>
        @error('address.ar')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="logo_image">Logo</label>
        <input type="file" id="logo_image" name="logo_image" class="form-control" accept="image/*">
    </div>
    <input type="submit" name="save" class="btn btn-danger" value="Save">
</form>

                </div>
            </div>
        </div>
    </div>
    <script type="module">
    import { handleImageInput } from '/js/pintura-handler.js';
    handleImageInput('#logo_image');
    </script>
@endsection
