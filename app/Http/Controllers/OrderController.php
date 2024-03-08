<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class OrderController extends Controller
{
    public $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
        $this->middleware('auth');
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * //@return \Illuminate\Http\Response
     */
    public function create()
    {
        $cart = $this->cartService->getFromCookie();

        if( !isset($cart) || $cart->products->isEmpty() ){
            return redirect()
                    ->back()
                    ->withErrors("Your cart is empty!");
        }

        return view("orders.create")->with([
            'cart' => $cart,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * //@return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        //dd($user);

        $order = $user->orders()->create([
            'status' => 'pending',
        ]);
        //dd($order);

        $cart = $this->cartService->getFromCookie();
        //dd($cart->products);

        $cartProductsWithQuantity = $cart
            ->products
            ->mapWithKeys( function($product) {
                $element[$product->id] = [ 'quantity' => $product->pivot->quantity ];
                return $element;
            });
        //dd($cartProductsWithQuantity);

        $order->products()->attach($cartProductsWithQuantity->toArray());

        return redirect()->route( 'orders.payments.create', ['order' => $order] );

    }

}
