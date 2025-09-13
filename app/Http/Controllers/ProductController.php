<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Scopes\ActiveScope;
use Illuminate\Http\Request;

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



        // subquery
        // average rating Query+subquery 
        $products = Product::whereIn('id', function ($query) {
            $query->select('product_id')
                ->from('reviews')
                ->groupBy('product_id')
                ->havingRaw("AVG(rating) > ?", [4]);
        })
            // only those products should come whose qty greater then (number) Query+subquery 
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('inventories')
                    ->where('quantity', '>', 10);
            })
            // only those products should come whose reviews are created in last 30 days
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('reviews as reviews2')
                    ->where('created_at', '>', now()->subMonth());
            })->get();


        $productsSubQuery = Product::select('products.*')
            ->selectSub(function ($query) {
                $query->from('reviews')
                    ->selectRaw('AVG(rating)')
                    ->whereColumn('product_id', 'products.id');
            }, 'avg_rating')
            ->having('avg_rating', '>', 4)
            ->get();
        // return $products;
        // dump($productsSubQuery->toArray());
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
