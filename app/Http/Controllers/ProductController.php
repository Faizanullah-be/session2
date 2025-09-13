<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Scopes\ActiveScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {
        // Global Scope
        // $products = Product::withoutGlobalScope(ActiveScope::class)->get();
        // // // Local Scope
        // $products = Product::Inactive()->get();
        // $products = Product::HasDiscount()->get();
        // $products = Product::HasNoDiscount()->get();
        // $products = Product::InStock()->get();
        // $products = Product::OutOfStock()->get();
        // $products = Product::Expired()->get();
        // $products = Product::NotExpired()->get();
        // $stars = 4;
        // $products = Product::WithRatings($stars)->get();
        // return $products;



        // Eloquent  (QueryBuilder + subquery)
        // only those products should come whose average rating is greater then 4   
        $requestedProducts = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $products = DB::table('products')->whereIn('id', $requestedProducts)
            // only those products should come whose average rating is greater then 4F
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('reviews')
                    ->groupBy('product_id')
                    ->havingRaw("AVG(rating) > ?", [4]);
            })
            // only those products should come whose qty greater then 10
            ->whereIn('id', $requestedProducts)
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('inventories')
                    ->groupBy('product_id')
                    ->where('quantity', '>', 10);
            })
            // only those products should come whose reviews are created in last 30 days
            ->whereIn('id', $requestedProducts)
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('reviews as review2')
                    ->groupBy('product_id')
                    ->where('created_at', '>=', now()->subMonth());
            })->get();

        // dump($products->toArray());
        return $products;
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
