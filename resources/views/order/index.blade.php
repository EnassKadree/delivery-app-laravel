@extends('admin.dashboard')
@section('content')

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Orders</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>Id</th>
                            <th>Status</th>
                            <th>Total Price</th>
                            <th>Address</th>
                            <th>Customer</th>
                            <th>Edit Status</th>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ ucfirst($order->status) }}</td>
                                    <td>${{ number_format($order->total_price, 2) }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->customer->first_name}} {{$order->customer->last_name }}</td>
                                    <td style="text-align: center;">
                                        <a href="{{ route('admin.order.edit', ['order' => $order->id]) }}" class="btn btn-success">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No Orders Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
