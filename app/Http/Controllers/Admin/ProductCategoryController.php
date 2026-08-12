<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
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

        $categories = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        return view('admin.product-category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.product-category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|array|min:1',
            'title.*'   => 'required|string|max:255',
            'image.*'   => 'nullable|image|max:2048',
            'content.*' => 'nullable|string',
        ]);

        foreach ($request->title as $index => $title) {
            $data = [
                'title'   => $title,
                'content' => $request->content[$index] ?? null,
                'status'  => 'active',
            ];

            if ($request->hasFile('image.' . $index)) {
                $data['image'] = $request->file('image')[$index]->store('product-category', 'public');
            }

            ProductCategory::create($data);
        }

        return redirect()->route('admin.product-category.index')->with('success', 'Product category/categories added successfully.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-category.edit', ['category' => $productCategory]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'image'   => 'nullable|image|max:2048',
            'content' => 'nullable|string',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('image')) {
            if ($productCategory->image) {
                Storage::disk('public')->delete($productCategory->image);
            }
            $data['image'] = $request->file('image')->store('product-category', 'public');
        }

        $productCategory->update($data);

        return redirect()->route('admin.product-category.index')->with('success', 'Product category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->image) {
            Storage::disk('public')->delete($productCategory->image);
        }

        $productCategory->delete();

        return response()->json(['message' => 'Product category deleted successfully.']);
    }
}