@extends('layouts.app')

@section('title', 'Update Account')

@section('content')

<div class="container mt-5 pb-5">

    <div class="row">
        <div class="col-lg-3">

            @include('partials.sidebar')

        </div>


        <div class="col-lg-9">

            <h1 class="h4 mb-3">Update Account</h1>

            <form action="{{ route('update.account.store') }}" method="POST" class="col-lg-7 rounded p-3 border"
                onsubmit="return confirm('Are you sure?');">

                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" autocomplete="given-name"
                        value="{{ old('first_name', auth()->user()->first_name) }}" @error('first_name') aria-invalid="true" aria-describedby="first_name-error" @enderror>
                    @error('first_name')
                    <small id="first_name-error" class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" autocomplete="family-name"
                        value="{{ old('last_name', auth()->user()->last_name) }}" @error('last_name') aria-invalid="true" aria-describedby="last_name-error" @enderror>
                    @error('last_name')
                    <small id="last_name-error" class="text-danger">{{ $message }}</small>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" autocomplete="email"
                        value="{{ old('email', auth()->user()->email) }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    @error('email')
                    <small id="email-error" class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mb-2">Update</button>

            </form>

            <div class="col-lg-7 border-top mt-4 pt-3">
                <p class="text-muted">Delete all of my account information? This also removes your order history. Enter your password to confirm.</p>
                <form action="{{ route('update.account.destroy') }}" method="POST" onsubmit="return confirm('Confirm account deletion? This action cannot be reverted.');">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label for="delete_current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="delete_current_password" name="current_password" autocomplete="current-password" required @error('current_password') aria-invalid="true" aria-describedby="delete_current_password-error" @enderror>
                        @error('current_password')
                        <small id="delete_current_password-error" class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button class="btn btn-danger" type="submit">Delete Account</button>
                </form>
            </div>


        </div>
    </div>
</div>
@endsection