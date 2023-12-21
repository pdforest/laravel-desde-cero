<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index() {

        //$products = DB::table('products')->get();     // mediante Query Builder
        //$products = Product::all();                     // mediante los modelos y el ORM Eloquent

        //return $products;     // Larevel lo devuelve formateado en json 
        //dd($products);        // usar debug() o dd() debug and die 

        return view('products.index')->with([
            //'products' => Product::all();
            'products' => DB::table('products')->orderBy('id', 'desc')->get(),
        ]);
    }
    
    public function create() {
        return view('products.create');
    }

    public function store() {

        $rules = [
            'title' => ['required', 'max:256'],
            'description' => ['required', 'max:1000'],
            'price' => ['required', 'min:1'],
            'stock' => ['required', 'min:0'],
            'status' => ['required', 'in:available,unavailable'],
        ];

        request()->validate($rules);

        if( request()->status == 'available' && request()->stock == 0 ){
            //session()->put('error', 'If available must have stock');
            //session()->flash('error', 'If available must have stock');
            
            return redirect()
                ->back()
                ->withInput(request()->all())
                ->withErrors('If available must have stock');
        }
        
        /*$product = Product::create([
            'title' => request('title'),
            'description' => request('description'),
            'price' => request('price'),
            'stock' => request('stock'),
            'status' => request('status'),
        ]);*/
        
        $product = Product::create(request()->all());
        //session()->flash('success', "The new product with id {$product->id} was created");

        //return redirect()->back();
        //return redirect()->action('MainController@index');
        return redirect()
            ->route('products.index')
            ->withSuccess("The new product with id {$product->id} was created");
            //->with(['success' => "The new product with id {$product->id} was created"]); // es lo mismo que withSuccess
    }

    public function show(Product $product) {

        //$product = DB::table('products')->where('id', $product)->first();
        //$product = DB::table('products')->find($product);
        
        // Se puede omitir por la inyeccion implicita de modelos function(Product $product)
        //$product = Product::findOrFail($product);

        //return $product;
        //dd($product);

        return view('products.show')->with([
            'product' => $product,
        ]);
    }

    public function edit(Product $product) {

        return view('products.edit')->with([
            //'product' => Product::findOrFail($product),
            'product' => $product,
        ]);
    }

    public function update(Product $product) {

        $rules = [
            'title' => ['required', 'max:256'],
            'description' => ['required', 'max:1000'],
            'price' => ['required', 'min:1'],
            'stock' => ['required', 'min:0'],
            'status' => ['required', 'in:available,unavailable'],
        ];

        request()->validate($rules);

        //lo buscamos de nuevo para verificar que exista
        // no se necesita por la inyeccion
        //$product = Product::findOrFail($product);

        $product->update(request()->all());

        return redirect()
            ->route('products.index')
            ->withSuccess("The product with id {$product->id} was edited");
    }

    public function destroy(Product $product) {

        //$product = Product::findOrFail($product);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->withSuccess("The product with id {$product->id} was deleted");
    }

}
