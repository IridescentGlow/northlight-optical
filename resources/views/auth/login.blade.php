@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="container mt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h1 class="h4">Login</h1>
            <hr>

            <form action="{{ route('login.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="username" value="{{ old('email') }}">
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" value="{{ old('password') }}">
                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mb-2">Login</button>
            </form>

            <p class="mt-3 text-muted">Don't have an account? <a href="{{ route('register.create') }}">Register</a></p>
        
        </div>
    </div>
</div>



@endsection