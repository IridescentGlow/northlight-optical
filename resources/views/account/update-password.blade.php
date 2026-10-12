@extends('layouts.app')

@section('title', 'Update Password')

@section('content')

<div class="container mt-5 pb-5">

    <div class="row">
        <div class="col-lg-3">

            @include('partials.sidebar')

        </div>


        <div class="col-lg-9">
            <h4 class="mb-3">Update Password</h4>

            <form action="{{ route('update.password.store') }}" method="POST" class="col-lg-7 rounded p-3 border" onsubmit="return confirm('Are you sure?');">

                @csrf

                <div class="mb-3">
                    <label for="password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" autocomplete="current-password" value="{{ old('current_password') }}">
                    @error('current_password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password" value="{{ old('new_password') }}">
                    @error('new_password')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
                    @error('password_confirmation')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mb-2">Update</button>

            </form>

            
        </div>
    </div>
</div>
@endsection