@extends('layouts.app')

@section('title', 'Contact & Booking')

@section('content')

<div class="container my-5 pb-5">

    <div class="reveal">
        <h4 class="mb-2">Contact &amp; Booking</h4>
        <p class="text-muted mb-4">Send us a message or request an appointment — we'll follow up within one business day.</p>
    </div>

    <div class="row">
        <div class="col-lg-7 reveal">

            <form action="{{ route('contact.store') }}" method="POST">

                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="phone_number">Phone No.</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                        @error('phone_number')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="reason">Reason for Contact <span class="text-danger">*</span></label>
                        <select class="form-select" id="reason" name="reason">
                            <option value="" disabled {{ old('reason') ? '' : 'selected' }}>Choose...</option>
                            @foreach($reasons as $reason)
                            <option value="{{ $reason }}" {{ old('reason') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                            @endforeach
                        </select>
                        @error('reason')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="preferred_date">Preferred Date</label>
                    <input type="date" class="form-control" id="preferred_date" name="preferred_date" value="{{ old('preferred_date') }}">
                    @error('preferred_date')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="message">Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="message" rows="4" class="form-control"
                        placeholder="Tell us what you need — a specific time that works, accessibility needs, insurance questions, anything.">{{ old('message') }}</textarea>
                    @error('message')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary px-3 py-2 mb-4">Send Request</button>

            </form>

        </div>

        <div class="col-lg-5 mt-lg-3 reveal">

            <div class="card border-0 bg-light-subtle p-4 hover-lift">

                <h5 class="mb-3">{{ $business['name'] }}</h5>

                <p class="d-flex mb-3">
                    @include('partials.icon', ['icon' => 'map-pin', 'size' => 20])
                    <span class="ms-2">
                        {{ $business['address_line1'] }}<br>
                        {{ $business['address_line2'] }}<br>
                        <a href="{{ $business['maps_url'] }}" target="_blank" rel="noopener" class="small">Get Directions</a>
                    </span>
                </p>

                <p class="d-flex mb-3">
                    @include('partials.icon', ['icon' => 'phone', 'size' => 20])
                    <span class="ms-2">
                        <a href="tel:{{ $business['phone_href'] }}" class="text-body">{{ $business['phone'] }}</a>
                    </span>
                </p>

                <p class="d-flex mb-3">
                    @include('partials.icon', ['icon' => 'mail', 'size' => 20])
                    <span class="ms-2">
                        <a href="mailto:{{ $business['email'] }}" class="text-body">{{ $business['email'] }}</a>
                    </span>
                </p>

                <p class="d-flex mb-0">
                    @include('partials.icon', ['icon' => 'clock', 'size' => 20])
                    <span class="ms-2">
                        @foreach($hours as $line)
                        {{ $line['label'] }}: {{ $line['value'] }}<br>
                        @endforeach
                    </span>
                </p>

            </div>

        </div>
    </div>

</div>

@endsection
