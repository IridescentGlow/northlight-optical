@extends('layouts.app')


@section('title', 'My Orders')


@section('content')

<div class="container mt-5 pb-5">

    <div class="row">
        <div class="col-lg-3">

            @include('partials.sidebar')

        </div>

        <div class="col-lg-9">

            <h1 class="h4 mb-3">My Orders</h1>


            @if($orders->count() > 0)

            <div class="table-responsive">
                <table class="table table-bordered" style="min-width: 50rem;">
                    <thead class="small">
                        <tr>
                            <th scope="col">Order No.</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Address</th>
                            <th scope="col">Total</th>
                            <th scope="col"><span class="visually-hidden">Details</span></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($orders as $order)
                         
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                            <td>
                                <span class="badge bg-primary fw-normal">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>{{ $order->address . ', ' . $order->postal  }}</td>
                            <td>${{ $order->total }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                type="button" data-bs-toggle="modal" data-bs-target="#order-{{ $order->id }}"
                                aria-label="View details of order {{ $order->order_number }}">
                                View More
                               </button>

                               @include('partials.modal')
                                
                    
                            </td>
                        </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>


            
            <div class="text-end">
                {{ $orders->links() }}
            </div>


            @else

            <p class="text-muted">No Orders Found</p>

            @endif


        </div>
    </div>
</div>
@endsection