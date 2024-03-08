<div class="card mb-3">
    <img class="card-img-top" src="{{ asset($product->images->first()->path) }}" height="500">
    <div class="card-body">
        <h4 class="text-end"><strong>$ {{$product->price}}</strong></h4>
        <h4 class="card-title">{{$product->title}}</h4>
        <p class="card-text">{{$product->description}}</p>
        <p class="card-text"><strong>{{$product->stock}} left</strong></p>
        @if (isset($cart))
            <p class="card-text"> {{ $product->pivot->quantity }} in your cart <strong>( $ {{$product->total}} )</strong></p>
            <form
                class="d-inline"
                method="POST"
                action="{{ route('products.carts.destroy', [ 'cart' => $cart->id, 'product' => $product->id]) }}"
                >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning">Remove from Cart</button>
            </form>
        @else
            <form
                class="d-inline"
                method="POST"
                action="{{ route('products.carts.store', ['product' => $product->id]) }}"
                >
                @csrf
                <button type="submit" class="btn btn-success">Add To Cart</button>
            </form>
        @endif
    </div>
</div>
