<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Delivery Management System') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div id="wrapper-admin">
        <div class="container">
            <div class="row">
                <div class="offset-md-4 col-md-4">
                    <div class="logo border-danger">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" width="70" height="100">
                    </div>
                    <form class="yourform" action="{{ route('login.store') }}" method="post">
                        @csrf
                        <h3 class="heading">Login</h3>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="" required>
                        </div>
                        <br>
                        <div style="text-align: center;">
                            <input type="submit" name="login" class="btn btn-danger" value="Login" style="width: 300px;" />
                        </div>
                        <br/>
                    </form>
                    @error('email')
                    <div class='alert alert-danger'>{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</body>

</html>
