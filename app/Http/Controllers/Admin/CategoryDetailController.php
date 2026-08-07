<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryDetailController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductDetail::with(['category', 'brand']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('product_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sortBy    = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['id', 'title', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $products   = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();
        $categories = ProductCategory::where('status', 'active')->orderBy('title')->get();

        return view('admin.category-details.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::where('status', 'active')->orderBy('title')->get();
        $brands     = Brand::where('status', 'active')->orderBy('title')->get();

        return view('admin.category-details.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'brand_id'             => 'nullable|exists:brands,id',
            'title'                => 'required|string|max:255',
            'image'                => 'nullable|image|max:2048',
            'content'              => 'nullable|string|max:255',
        ]);

        $data = [
            'product_category_id' => $request->product_category_id,
            'brand_id'             => $request->brand_id,
            'title'                => $request->title,
            'url'                  => Str::slug($request->title),
            'content'              => $request->content,
            'status'               => 'active',
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-details', 'public');
        }

        ProductDetail::create($data);

        return redirect()->route('admin.category-details.index')->with('success', 'Product added successfully.');
    }

    public function edit(ProductDetail $categoryDetail)
    {
        $categories = ProductCategory::where('status', 'active')->orderBy('title')->get();
        $brands     = Brand::where('status', 'active')->orderBy('title')->get();

        return view('admin.category-details.edit', [
            'product'    => $categoryDetail,
            'categories' => $categories,
            'brands'     => $brands,
        ]);
    }

    public function update(Request $request, ProductDetail $categoryDetail)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'brand_id'             => 'nullable|exists:brands,id',
            'title'                => 'required|string|max:255',
            'image'                => 'nullable|image|max:2048',
            'content'              => 'nullable|string|max:255',
        ]);

        $data = $request->only('product_category_id', 'brand_id', 'title', 'content');
        $data['url'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            if ($categoryDetail->image) {
                Storage::disk('public')->delete($categoryDetail->image);
            }
            $data['image'] = $request->file('image')->store('product-details', 'public');
        }

        $categoryDetail->update($data);

        return redirect()->route('admin.category-details.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(ProductDetail $categoryDetail)
    {
        if ($categoryDetail->image) {
            Storage::disk('public')->delete($categoryDetail->image);
        }

        $categoryDetail->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}