<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        //return view('products.index', ['products'=> Product::latest()->get()]);
        return view('products.index', ['products'=> Product::latest()-> paginate(3)]);
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        //dd($request->all());
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:10000'
        ]);
        //Upload image
        $imageName = time(). '.'.$request->image->extension();
        $request->image->move(public_path('products'), $imageName);

        //dd($imageName);
        $product = new Product;
        $product->image = $imageName;
        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        return back()->withSuccess('Product created !!!');
    }

    public function edit($id) {
        //dd($id);
        $product = Product::where('id', $id)->first();
        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:10000'
        ]);

        $product = Product::where('id', $id)->first();

        if(isset($request->image)) {
            $imageName = time(). '.'.$request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $product->image = $imageName;
        }

        $product->name = $request->name;
        $product->description = $request->description;

        $product->save();

        return back()->withSuccess('Product updated !!!');
    }

    public function destroy($id) {
        $product = Product::where('id', $id)->first();
        $product->delete();
        return back()->withSuccess('Product deleted !!!');
    }

    public function show($id) {
        $product = Product::where('id', $id)->first();
        return view('products.show', ['product'=> $product]);
    }
}
