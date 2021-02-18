<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Requests\ProductRequest;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\NotBelongsToUser;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show','index1');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        // return ProductCollection::collection(Product::paginate(20));
        return ProductResource::collection($category->products);
    }

    // public function index1(Category $category)
    // {
    //     return ProductResource::collection($category->products);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;
        $product->category_id = $request->category_id;
        $product->user_id = Auth::id();
        $product->name = $request->name;
        $product->detail = $request->description;
        $product->stock = $request->stock;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->save();
        return response([
            'data' => new ProductResource($product)
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->ProductUserCheck($product);
        $request['detail'] = $request->description;
        $request['category_id'] = $request->category_id;
        // unset($request['description']);
        $request['name'] = $request->name;
        $request['stocks'] = $request->stocks;
        $request['price'] = $request->price;
        $request['discount'] = $request->discount;
        $product->update($request->all());
        return response([
            'data' => new ProductResource($product)
        ],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->ProductUserCheck($product);
        $product->delete();

        return response(null,204);
    }
    public function ProductUserCheck($product)
    {
        if (Auth::id() !== $product->user_id) {
            throw new NotBelongsToUser;
        }

    }
}
