<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\CartService;


class OrderPaymentController extends Controller
{
    public $cartService;

    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
        $this->middleware('auth'); //se pone para proteger la ruta
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function create(Order $order)
    {
        return view('payments.create')->with([
            'order' => $order,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Order $order)
    {
        //Asumimos que ya se efectuo un pago
        //PaymentService::handlePayment()

        $cart = $this->cartService->getFromCookie()->products()->detach();  //solo lo vacia

        $order->payment()->create([
            'amount' => $order->total,
            'payed_at' => now(),
        ]);

        $order->status = 'payed';
        $order->save();

        return redirect()
            ->route('main')
            ->withSuccess("Thanks! Your payment for \$ {$order->total} was successful.");

    }

}
