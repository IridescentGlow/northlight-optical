@extends('layouts.app')

@section('title', 'Register')


@section('content')

<div class="container mt-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h4>Register</h4>
            
            <hr>

            <form action="{{ route('register.store') }}" method="POST">

                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" autocomplete="given-name" value="{{ old('first_name') }}">
                        @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror    
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" autocomplete="family-name" value="{{ old('last_name') }}">
                        @error('last_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror    
                    </div>
                </div>


                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="email" value="{{ old('email') }}">
                    @error('email')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" autocomplete="new-password" value="{{ old('password') }}">
                    @error('password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                    @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mb-2">Register</button>
            </form>

            <p class="mt-3 text-muted">Already have an account? <a href="{{ route('login.create') }}">Login</a></p>
        </div>
    </div>
</div>



@endsection