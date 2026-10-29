<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /** GET /api/admin/products — all products (incl. inactive). */
    public function index()
    {
        return Product::orderBy('sort_order')->orderBy('id')->get();
    }

    /** GET /api/admin/products/{id} */
    public function show(int $id)
    {
        return Product::findOrFail($id);
    }

    /** POST /api/admin/products */
    public function store(Request $request)
    {
        $product = Product::create($this->validateData($request));

        return response()->json($product, 201);
    }

    /** PUT /api/admin/products/{id} */
    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
        $product->update($this->validateData($request, $product->id));

        return response()->json($product);
    }

    /** DELETE /api/admin/products/{id} */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($ignoreId)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'num' => ['nullable', 'string', 'max:50'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_long' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'price' => ['required', 'integer', 'min:0'],
            'accent' => ['nullable', 'string', 'max:255'],
            'accent_strong' => ['nullable', 'string', 'max:255'],
            'use_img' => ['boolean'],
            'image' => ['nullable', 'string', 'max:1024'],
            'details' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);
    }
}
