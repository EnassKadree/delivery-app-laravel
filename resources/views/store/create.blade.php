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
                    <form class="yourform" action="{{ route('admin.store.create') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Store Name</label>
                            <input type="text" class="form-control @error('name.en') is-invalid @enderror"
                                placeholder="Store Name" name="name[en]" value="{{ old('name.en') }}" required>
                            @error('name.en')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control @error('address.en') is-invalid @enderror"
                                placeholder="Address" name="address[en]" value="{{ old('address.en') }}" required>
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
                        <input type="submit" name="save" class="btn btn-danger" value="save" required>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
