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
                <h2 class="admin-heading">Update Store</h2>
            </div>
        </div>
        <div class="row">
            <div class="offset-md-3 col-md-6">
                <form class="yourform" action="{{ route('admin.store.update', $store->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Store Name (English)</label>
                        <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                            placeholder="Store Name" name="name[en]" value="{{ old('name.en', $store->getTranslation('name', 'en') ?? '') }}" required>
                        @error('name.en')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Store Name (Other Language)</label>
                        <input type="text" class="form-control @error('name.ar') is-invalid @enderror"
                            placeholder="Store Name (Other Language)" name="name[ar]" value="{{ old('name.ar', $store->getTranslation('name', 'ar') ?? '') }}">
                        @error('name.ar')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Address (English)</label>
                        <input type="text" class="form-control @error('address.en') is-invalid @enderror"
                            placeholder="Address" name="address[en]" value="{{ old('address.en', $store->getTranslation('address', 'en') ?? '') }}" required>
                        @error('address.en')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Address (Other Language)</label>
                        <input type="text" class="form-control @error('address.ar') is-invalid @enderror"
                            placeholder="Address (Other Language)" name="address[ar]" value="{{ old('address.ar', $store->getTranslation('address', 'ar') ?? '') }}">
                        @error('address.ar')
                            <div class="alert alert-danger" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="logo_image">Logo</label>
                        <div class="mb-2">
                            @if ($store->logo_image)
                                <img src="{{ asset('storage/' . $store->logo_image) }}" class="img-thumbnail" width="100">
                            @endif
                        </div>
                        <input type="file" id="logo_image" name="logo_image" class="form-control" accept="image/*">
                    </div>
                    <input type="submit" name="save" class="btn btn-danger" value="Update">
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
