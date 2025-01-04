@extends('admin.dashboard')
@section('content')

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Stores</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('admin.store.create') }}">Add Store</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>Id</th>
                            <th>Store Name</th>
                            <th>Address</th>
                            <th>Logo</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($stores as $store)
                                <tr>
                                <td>{{ $store['id'] }}</td>
                                <td>{{ $store['name'] }}</td>
                                <td>{{ $store['address'] }}</td>
                                <td>
                                    <img src="{{ asset($store['image']) }}" alt="{{ $store['name'] }}" width="50" height="50">
                                </td>
                                <td class="edit" style="text-align: center;">
                                    <a href="{{ route('admin.store.edit', ['store' => $store['id']]) }}" class="btn btn-success">Edit</a>
                                </td>
                                <td class="delete" style="text-align: center;">
                                    <form action="{{ route('admin.store.destroy', ['id' => $store['id']]) }}" method="POST" class="form-hidden">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger delete-store">Delete</button>
                                    </form>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No Stores Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
