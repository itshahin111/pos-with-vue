<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{


    function productPage()
    {
        return Inertia::render('ProductPage');
    }

    function storeProduct(Request $request)
    {
        $user_id = $request->header('id');
        return Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id'),
            'user_id' => $user_id
        ]);

    }

    function showProducts(Request $request)
    {
        $user_id = $request->header('id');
        return Product::where('user_id', $user_id)->get();
    }

    function editProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        return Product::where('id', $product_id)->where('user_id', $user_id)->first();


    }


    function updateProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        return Product::where('id', $product_id)->where('user_id', $user_id)->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'category_id' => $request->input('category_id')
        ]);

    }
    function destroyProduct(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        return Product::where('id', $product_id)->where('user_id', $user_id)->delete();
    }

}
