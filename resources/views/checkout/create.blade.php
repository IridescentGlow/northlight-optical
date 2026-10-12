@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="container mt-5 pb-5">


	<h4 class="mb-2">Checkout</h4>

	<div class="row">
		<div class="col-lg-7">

            <form action="{{ route('checkout.store') }}" method="POST">

                <h5 class="mt-4">Customer Information</h5>
                <hr>
                
                @csrf

                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control hover:not-allowed" id="first_name" name="first_name" value="{{ auth()->user()->first_name  }}" readonly>
                        @error('first_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control hover:not-allowed" id="last_name" name="last_name" value="{{ auth()->user()->last_name }}" readonly>
                        @error('last_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            
                <div class="form-group row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="email" class="text-black">Email Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control hover:not-allowed" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="phone_number" class="text-black">Phone No.<span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" autocomplete="tel" value="{{ old('phone_number') }}">
                        @error('phone_number')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            
                <div class="form-group row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="address" class="text-black">Address<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" autocomplete="street-address" maxlength="150" placeholder="Street address" value="{{ old('address') }}">
                        @error('address')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
            
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="postal" class="text-black">Postal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="postal" name="postal" autocomplete="postal-code" value="{{ old('postal') }}">
                        @error('postal')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="form-label" for="order_notes" class="text-black">Order Notes</label>
                    <textarea name="order_notes" maxlength="150" id="order_notes" cols="30" rows="3" class="form-control"
                     placeholder="Write your notes here...">{{ old('order_notes') }}</textarea>
                    @error('order_notes')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            
                <h5 class="mt-4">Payment Information</h5>
                <hr>

                {{-- Northlight is a concept project and no payment is taken, so say
                     so before anyone types a real card. The card fields also opt out
                     of browser autofill for the same reason. --}}
                <p class="alert alert-light border small" role="note">
                    This is a concept store. No payment is processed and card details are not saved.
                    Please don't enter a real card; any 16 digits, a MM/YY from this year or later and any 3-digit code will work.
                </p>
            
                <div class="mb-3">
                    <label for="card_number" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" autocomplete="off" inputmode="numeric" value="{{ old('card_number') }}">
                    @error('card_number')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" autocomplete="off" maxlength="5" placeholder="MM/YY" value="{{ old('expiry_date') }}">
                        @error('expiry_date')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
            
                    <div class="col-md-6 mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="number" class="form-control" id="cvv" name="cvv" autocomplete="off" inputmode="numeric" value="{{ old('cvv') }}">
                        @error('cvv')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            
                <button type="submit" class="btn btn-primary px-3 py-2 mb-4">Place Order</button>
            </form>
            
		</div>

		<div class="col-lg-5 mt-lg-3">
			<table class="table border mt-lg-5">
				<thead>
					<tr>
						<td colspan="2">
							<h4 class="mb-0">Cart Totals</h4>
						</td>
					</tr>
				</thead>
				<tbody class="fw-bold">
                    <tr>
                        <td>Sub Total</td>
                        <td>${{ $subTotal }}</td>
                    </tr>
                    <tr>
                        <td>Tax (5%)</td>
                        <td>${{ number_format($subTotal * 0.05, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>${{ number_format($subTotal + ($subTotal * 0.05), 2) }}</td>
                    </tr>
                </tbody>
                
			</table>
		</div>
	</div>


</div>


@endsection