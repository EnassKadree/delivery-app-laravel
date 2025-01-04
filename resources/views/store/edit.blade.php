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
                    <form class="yourform" action="{{ route('admin.store.update', $store->id) }}" method="post"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Store Name (English)</label>
                            <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                                placeholder="Store Name" name="name[en]" value="{{ $store->name['en'] ?? '' }}" required>
                            @error('name.en')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Address (English)</label>
                            <input type="text" class="form-control @error('address.en') is-invalid @enderror"
                                placeholder="Address" name="address[en]" value="{{ $store->address['en'] ?? '' }}" required>
                            @error('address.en')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo_image">Logo</label>
                            <input type="file" class="form-control @error('logo_image') is-invalid @enderror" id="logo_image" name="logo_image" accept="image/*">
                            @error('logo_image')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="submit" name="save" class="btn btn-danger" value="Update" >
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
