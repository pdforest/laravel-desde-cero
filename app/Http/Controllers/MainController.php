<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\Product;

class MainController extends Controller
{
    public function index() {
        return view('welcome')->with([
            'products' => DB::table('products')->orderBy('id', 'desc')->get(),
        ]);
    }
}
