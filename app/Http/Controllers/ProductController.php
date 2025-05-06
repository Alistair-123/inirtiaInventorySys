<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Constructor to apply middleware to specific methods
     */
    public function __construct()
    {
        // Apply admin middleware only to these methods
        $this->middleware('admin')->only(['update', 'destroy', 'adminOnly']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(9);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'isAdmin' => auth()->check() && auth()->user()->isAdmin(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // Add the authenticated user's ID
        $validated['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index');
    }

    /**
     * Update the specified resource in storage.
     * This method is protected by the admin middleware in the constructor
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Keep the existing image if no new image is uploaded
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     * This method is protected by the admin middleware in the constructor
     */
    public function destroy(Product $product)
    {
        // Delete the image if it exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }

    /**
     * Admin only page
     * This method is protected by the admin middleware in the constructor
     */
    public function adminOnly()
    {
        return Inertia::render('Admin/Products', [
            'products' => Product::latest()->paginate(10)
        ]);
    }
}
