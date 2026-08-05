<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\GiftingOccasion;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\Collection;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('images');

        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $subCategories = collect();

        if ($request->filled('category_id')) {
            $subCategories = Category::where('parent_id', $request->category_id)
                ->orderBy('name')
                ->get();
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        if ($request->filled('subcategory_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->subcategory_id);
            });
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['id', 'name', 'price', 'status'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(10)->appends($request->all());

        return view('admin.products.index', compact('products', 'categories', 'subCategories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $occasions = GiftingOccasion::where('status', 1)->get();

        $collections = Collection::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.create', compact('categories', 'occasions', 'collections'));
    }

    public function subcategories(Category $category)
    {
        return response()->json(
            Category::where('parent_id', $category->id)
                ->where('status', 1)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
    }

    public function categoryAttributes(Category $category)
    {
        $attributes = CategoryAttribute::with(['attribute.values'])
            ->where('category_id', $category->id)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return response()->json($attributes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'mrp' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:amount,percentage',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'min_qty' => 'nullable|integer|min:1',
            'sku' => 'nullable|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'hsn_code' => 'nullable|string|max:255',
            'card_default' => 'nullable|image|max:2048',
            'card_hover' => 'nullable|image|max:2048',
            'banner_images' => 'nullable|array|max:10',
            'banner_images.*' => 'image|max:2048',
            'story_image' => 'nullable|image|max:2048',
            'notes' => 'nullable|array',
            'notes.*.title' => 'nullable|string|max:255',
            'notes.*.subtitle' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $product = Product::create([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'sub_title' => $request->sub_title,
                'weight' => $request->weight,
                'description' => $request->description,
                'product_notes' => $request->notes ?? [],
                'how_to_use' => $request->how_to_use,
                'the_story' => $request->the_story,
                'detail_page_color' => $request->detail_page_color ?? '#B8832F',

                'mrp' => $request->mrp !== null && $request->mrp !== '' ? $request->mrp : 0,
                'discount_type' => $request->discount_type ?: 'amount',
                'discount' => $request->discount !== null && $request->discount !== '' ? $request->discount : 0,
                'price' => $request->price !== null && $request->price !== '' ? $request->price : ($request->mrp ?: 0),

                'sku' => $request->sku,
                'stock' => $request->stock !== null && $request->stock !== '' ? $request->stock : 0,
                'min_qty' => $request->min_qty !== null && $request->min_qty !== '' ? $request->min_qty : 1,
                'product_code' => $request->product_code,
                'hsn_code' => $request->hsn_code,
                'delivery_time' => $request->delivery_time,

                'quality' => $request->has('quality'),
                'pan_india' => $request->has('pan_india'),

                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,

                'status' => $request->status,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            // 1. Default card image
            if ($request->hasFile('card_default')) {
                $path = $request->file('card_default')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'default',
                    'is_default' => true,
                    // 'sort_order' => 0,
                ]);
            }

            // 2. Hover card image
            if ($request->hasFile('card_hover')) {
                $path = $request->file('card_hover')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'hover',
                    'is_default' => false,
                    // 'sort_order' => 1,
                ]);
            }

            // 3. Banner images
            if ($request->hasFile('banner_images')) {
                foreach ($request->file('banner_images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'image_type' => 'banner',
                        'is_default' => false,
                        // 'sort_order' => $i,
                    ]);
                }
            }


            // 4. Story image
            if ($request->hasFile('story_image')) {
                $path = $request->file('story_image')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'story',
                    'is_default' => false,
                    // 'sort_order' => 99,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Product Attributes
            |--------------------------------------------------------------------------
            */

            if ($request->filled('attribute_values')) {
                foreach ($request->attribute_values as $attributeId => $values) {
                    foreach ($values as $valueId) {
                        ProductAttributeValue::create([
                            'product_id' => $product->id,
                            'attribute_id' => $attributeId,
                            'attribute_value_id' => $valueId,
                        ]);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Variants
            |--------------------------------------------------------------------------
            */

            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {

                    $variantImage = null;

                    if (
                        isset($variantData['image']) &&
                        $variantData['image'] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        $variantImage = $variantData['image']->store('product-variants', 'public');
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'] ?? null,
                        'mrp' => $variantData['mrp'] ?? 0,
                        'discount_type' => $variantData['discount_type'] ?? 'amount',
                        'discount' => $variantData['discount'] ?? 0,
                        'price' => $variantData['price'] ?? 0,
                        'stock' => $variantData['stock'] ?? 0,
                        'image' => $variantImage,
                    ]);

                    if (!empty($variantData['values'])) {
                        foreach ($variantData['values'] as $valueId) {
                            ProductVariantValue::create([
                                'variant_id' => $variant->id,
                                'attribute_value_id' => $valueId,
                            ]);
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Occasions & Collections
            |--------------------------------------------------------------------------
            */

            if ($request->filled('occasions')) {
                $product->occasions()->sync($request->occasions);
            }

            if ($request->filled('collections')) {
                $product->collections()->sync($request->collections);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    public function edit(Product $product)
    {
        $product->load([
            'images',
            'attributeValues.attribute',
            'attributeValues.value',
            'variants.values.attributeValue',
            'occasions',
            'collections',
        ]);

        $categories = Category::whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $subcategories = Category::where('parent_id', $product->category_id)
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        $occasions = GiftingOccasion::where('status', 1)->get();

        $attributes = CategoryAttribute::with(['attribute.values'])
            ->where('category_id', $product->category_id)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->get();

        $selectedAttributeValues = $product->attributeValues
            ->pluck('attribute_value_id')
            ->toArray();

        $selectedOccasions = $product->occasions
            ->pluck('id')
            ->toArray();

        $existingVariants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'mrp' => $variant->mrp,
                'discount_type' => $variant->discount_type,
                'discount' => $variant->discount,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'image' => $variant->image,
                'variant_name' => $variant->values
                    ->map(fn($v) => $v->attributeValue->value)
                    ->implode(' / '),
            ];
        })->values();

        $collections = Collection::where('status', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'subcategories',
            'attributes',
            'selectedAttributeValues',
            'selectedOccasions',
            'occasions',
            'existingVariants',
            'collections'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'mrp' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:amount,percentage',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'min_qty' => 'nullable|integer|min:1',
            'sku' => 'nullable|string|max:255',
            'product_code' => 'nullable|string|max:255',
            'hsn_code' => 'nullable|string|max:255',
            'card_default' => 'nullable|image|max:2048',
            'card_hover' => 'nullable|image|max:2048',
            'banner_images' => 'nullable|array|max:10',
            'banner_images.*' => 'image|max:2048',
            'story_image' => 'nullable|image|max:2048',
            'notes' => 'nullable|array',
            'notes.*.title' => 'nullable|string|max:255',
            'notes.*.subtitle' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {

            $product->update([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'sub_title' => $request->sub_title,
                'weight' => $request->weight,
                'description' => $request->description,
                'product_notes' => $request->notes ?? [],
                'how_to_use' => $request->how_to_use,
                'the_story' => $request->the_story,
                'detail_page_color' => $request->detail_page_color ?? '#B8832F',

                'mrp' => $request->mrp !== null && $request->mrp !== '' ? $request->mrp : 0,
                'discount_type' => $request->discount_type ?: 'amount',
                'discount' => $request->discount !== null && $request->discount !== '' ? $request->discount : 0,
                'price' => $request->price !== null && $request->price !== '' ? $request->price : ($request->mrp ?: 0),

                'sku' => $request->sku,
                'stock' => $request->stock !== null && $request->stock !== '' ? $request->stock : 0,
                'min_qty' => $request->min_qty !== null && $request->min_qty !== '' ? $request->min_qty : 1,
                'product_code' => $request->product_code,
                'hsn_code' => $request->hsn_code,
                'delivery_time' => $request->delivery_time,

                'quality' => $request->has('quality'),
                'pan_india' => $request->has('pan_india'),

                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,

                'status' => $request->status,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            // 1. Replace default card image
            if ($request->hasFile('card_default')) {
                $old = $product->images()->where('image_type', 'default')->first();
                if ($old) {
                    Storage::disk('public')->delete($old->image);
                    $old->delete();
                }
                $path = $request->file('card_default')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'default',
                    'is_default' => true,
                    // 'sort_order' => 0,
                ]);
            }

            // 2. Replace hover card image
            if ($request->hasFile('card_hover')) {
                $old = $product->images()->where('image_type', 'hover')->first();
                if ($old) {
                    Storage::disk('public')->delete($old->image);
                    $old->delete();
                }
                $path = $request->file('card_hover')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'hover',
                    'is_default' => false,
                    // 'sort_order' => 1,
                ]);
            }

            // 3. Append new banner images
            if ($request->hasFile('banner_images')) {
                $nextOrder = $product->images()->where('image_type', 'banner')->count();
                foreach ($request->file('banner_images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $path,
                        'image_type' => 'banner',
                        'is_default' => false,
                        // 'sort_order' => $nextOrder + $i,
                    ]);
                }
            }

            // 4. Delete individually removed images (any type)
            if ($request->filled('delete_images')) {
                foreach ($request->delete_images as $imgId) {
                    $img = ProductImage::where('id', $imgId)
                        ->where('product_id', $product->id)
                        ->first();
                    if ($img) {
                        Storage::disk('public')->delete($img->image);
                        $img->delete();
                    }
                }
            }


            // 4. Replace story image
            if ($request->hasFile('story_image')) {
                $old = $product->images()->where('image_type', 'story')->first();
                if ($old) {
                    Storage::disk('public')->delete($old->image);
                    $old->delete();
                }
                $path = $request->file('story_image')->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'image_type' => 'story',
                    'is_default' => false,
                    // 'sort_order' => 99,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Product Attributes (sync)
            |--------------------------------------------------------------------------
            */

            $currentAttributes = ProductAttributeValue::where('product_id', $product->id)->get();

            $currentKeys = $currentAttributes->map(
                fn($row) => $row->attribute_id . '-' . $row->attribute_value_id
            )->toArray();

            $newKeys = [];

            foreach ($request->attribute_values ?? [] as $attributeId => $values) {
                foreach ($values as $valueId) {
                    $newKeys[] = $attributeId . '-' . $valueId;
                    ProductAttributeValue::firstOrCreate([
                        'product_id' => $product->id,
                        'attribute_id' => $attributeId,
                        'attribute_value_id' => $valueId,
                    ]);
                }
            }

            foreach ($currentAttributes as $row) {
                $key = $row->attribute_id . '-' . $row->attribute_value_id;
                if (!in_array($key, $newKeys)) {
                    $row->delete();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Variants
            |--------------------------------------------------------------------------
            */

            $existingVariantIds = [];

            if ($request->filled('variants')) {
                foreach ($request->variants as $variantData) {

                    if (!empty($variantData['id'])) {

                        $variant = ProductVariant::where('product_id', $product->id)
                            ->where('id', $variantData['id'])
                            ->first();

                        if (!$variant)
                            continue;

                        $existingVariantIds[] = $variant->id;

                    } else {
                        $variant = new ProductVariant();
                        $variant->product_id = $product->id;
                    }

                    $variantImage = $variant->image ?? null;

                    if (
                        isset($variantData['image']) &&
                        $variantData['image'] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        if ($variant->image) {
                            Storage::disk('public')->delete($variant->image);
                        }
                        $variantImage = $variantData['image']->store('product-variants', 'public');
                    }

                    $variant->fill([
                        'sku' => $variantData['sku'] ?? null,
                        'mrp' => $variantData['mrp'] ?? 0,
                        'discount_type' => $variantData['discount_type'] ?? 'amount',
                        'discount' => $variantData['discount'] ?? 0,
                        'price' => $variantData['price'] ?? 0,
                        'stock' => $variantData['stock'] ?? 0,
                        'image' => $variantImage,
                    ]);

                    $variant->save();

                    // Attach values only for brand-new variants
                    if (empty($variantData['id']) && !empty($variantData['values'])) {
                        foreach ($variantData['values'] as $valueId) {
                            ProductVariantValue::create([
                                'variant_id' => $variant->id,
                                'attribute_value_id' => $valueId,
                            ]);
                        }
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Delete removed variants
            |--------------------------------------------------------------------------
            */

            $variantsToDelete = ProductVariant::where('product_id', $product->id);

            if (!empty($existingVariantIds)) {
                $variantsToDelete->whereNotIn('id', $existingVariantIds);
            }

            foreach ($variantsToDelete->get() as $variant) {
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }
                ProductVariantValue::where('variant_id', $variant->id)->delete();
                $variant->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Occasions & Collections
            |--------------------------------------------------------------------------
            */

            $product->occasions()->sync($request->occasions ?? []);
            $product->collections()->sync($request->collections ?? []);

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        $product->delete();

        return response()->json(['message' => 'Product Deleted Successfully']);
    }


}