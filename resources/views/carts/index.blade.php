@extends('layouts.app')

@section('content')

@if (!isset($cart) || $cart->products->isEmpty())
    <h1>Your cart</h1>
    <div class="alert alert-warning">
        Your cart is empty.
    </div>
@else
    <div class="row">
        @foreach( $cart->products as $product)
            <div class="col-3">
                @include('components.product-card')
            </div>
        @endforeach
    </div>
@endif

@endsection
