<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Image;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(20)
        ->create()
        ->each(function ($user) {
            $image = Image::factory()
                ->user()
                ->make();

            $user->image()->save($image);
        });

        $orders = Order::factory(10)
        ->make()
        ->each(function ($order) use ($users) {
            $order->customer_id = $users->random()->id;
            $order->save();

            $payment = Payment::factory()->make();

            // $payment->order_id = $order->id;
            // $payment->save();

            $order->payment()->save($payment);
        });


        $carts = Cart::factory(20)->create();
        
        // \App\Models\User::factory(10)->create();
        // \App\Models\Product::factory(50)->create();
        // $products = Product::factory(50)->create();

        $products = Product::factory(50)
        ->create()
        ->each(function ($product) use ($orders, $carts) {
            $order = $orders->random();

            $order->products()->attach([
                $product->id => ['quantity' => mt_rand(1, 3)]
            ]);

            $cart = $carts->random();

            $cart->products()->attach([
                $product->id => ['quantity' => mt_rand(1, 3)]
            ]);

            $images = Image::factory(mt_rand(2, 4))->make();
            $product->images()->saveMany($images);
        });

    }
}
