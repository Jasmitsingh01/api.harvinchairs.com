<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller
{
    /**
     * Display a listing of best seller products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bestSellerProducts = Product::where('is_best_seller', true)
            ->with(['categories', 'shop'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.best-seller.index', compact('bestSellerProducts'));
    }

    /**
     * Show the form for selecting products as best sellers.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::where('is_best_seller', false)
            ->where('status', 'publish')
            ->with(['categories', 'shop'])
            ->orderBy('name')
            ->paginate(50);

        return view('admin.best-seller.create', compact('products'));
    }

    /**
     * Store newly selected best seller products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $request->input('product_ids');
        
        // Update products to be best sellers
        Product::whereIn('id', $productIds)->update(['is_best_seller' => true]);

        return redirect()->route('admin.best-seller.index')
            ->with('success', 'Products have been marked as best sellers successfully.');
    }

    /**
     * Display the specified best seller product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['categories', 'shop', 'productFeatures.featureValue'])
            ->where('is_best_seller', true)
            ->findOrFail($id);

        return view('admin.best-seller.show', compact('product'));
    }

    /**
     * Show the form for editing the specified best seller product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with(['categories', 'shop'])
            ->where('is_best_seller', true)
            ->findOrFail($id);

        return view('admin.best-seller.edit', compact('product'));
    }

    /**
     * Update the specified best seller product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('is_best_seller', true)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:publish,draft',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.best-seller.index')
            ->with('success', 'Best seller product updated successfully.');
    }

    /**
     * Remove products from best seller list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        $productIds = $request->input('product_ids');
        
        // Remove products from best sellers
        Product::whereIn('id', $productIds)->update(['is_best_seller' => false]);

        return redirect()->route('admin.best-seller.index')
            ->with('success', 'Products have been removed from best sellers successfully.');
    }

    /**
     * Mass destroy best seller products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        Product::whereIn('id', $request->input('ids'))->update(['is_best_seller' => false]);

        return redirect()->route('admin.best-seller.index')
            ->with('success', 'Best seller products removed successfully.');
    }

    /**
     * Update best seller status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'status' => 'required|boolean'
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $product->update(['is_best_seller' => $request->input('status')]);

        return response()->json([
            'success' => true,
            'message' => 'Best seller status updated successfully.'
        ]);
    }

    /**
     * Search products for best seller selection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $products = Product::where('is_best_seller', false)
            ->where('status', 'publish')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['categories', 'shop'])
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json($products);
    }
} 