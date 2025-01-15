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
                            <th  style="background-color:rgb(87, 96, 95);">Id</th>
                            <th  style="background-color:rgb(87, 96, 95);">Store Name (English)</th>
                            <th  style="background-color:rgb(87, 96, 95);">Store Name (Arabic)</th>
                            <th  style="background-color:rgb(87, 96, 95);">Address (English)</th>
                            <th  style="background-color:rgb(87, 96, 95);">Address (Arabic)</th>
                            <th  style="background-color:rgb(87, 96, 95);">Logo</th>
                            <th  style="background-color:rgb(87, 96, 95);">Edit</th>
                            <th  style="background-color:rgb(87, 96, 95);">Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($stores as $store)
                                <tr>
                                    <td>{{ $store['id'] }}</td>
                                    <td>{{ $store->getTranslation('name', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $store->getTranslation('name', 'ar') ?? 'N/A' }}</td>
                                    <td>{{ $store->getTranslation('address', 'en') ?? 'N/A' }}</td>
                                    <td>{{ $store->getTranslation('address', 'ar') ?? 'N/A' }}</td>
                                    <td>
                                        @if (!empty($store['logo_image']))
                                            <a href="{{ asset('storage/' . $store['logo_image']) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $store['logo_image']) }}" alt="{{ $store->getTranslation('name', 'en') }}" width="50" height="50">
                                            </a>
                                        @else
                                            <img src="{{ asset('storage/placeholder-logo.png') }}" alt="No Logo" width="50" height="50">
                                        @endif
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
