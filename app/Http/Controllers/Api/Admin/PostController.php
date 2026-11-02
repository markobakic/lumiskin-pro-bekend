<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /** GET /api/admin/posts — all posts (incl. unpublished). */
    public function index()
    {
        return Post::orderByDesc('date')->orderByDesc('id')->get()
            ->map(fn ($p) => array_merge($p->toArray(), [
                'image_url' => $p->image ? asset('storage/' . $p->image) : null,
            ]));
    }

    /** GET /api/admin/posts/{id} */
    public function show(int $id)
    {
        $post = Post::findOrFail($id);

        return array_merge($post->toArray(), [
            'image_url' => $post->image ? asset('storage/' . $post->image) : null,
        ]);
    }

    /** POST /api/admin/posts */
    public function store(Request $request)
    {
        $post = Post::create($this->validateData($request));

        return response()->json(array_merge($post->toArray(), [
            'image_url' => null,
        ]), 201);
    }

    /** PUT /api/admin/posts/{id} */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);
        $post->update($this->validateData($request, $post->id));

        return response()->json(array_merge($post->toArray(), [
            'image_url' => $post->image ? asset('storage/' . $post->image) : null,
        ]));
    }

    /** DELETE /api/admin/posts/{id} */
    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['ok' => true]);
    }

    /** POST /api/admin/posts/{id}/image — upload or replace cover image. */
    public function uploadImage(Request $request, int $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        // Obrišemo staru sliku ako postoji
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $path = $request->file('image')->store('posts', 'public');
        $post->update(['image' => $path]);

        return response()->json([
            'image' => $path,
            'image_url' => asset('storage/' . $path),
        ]);
    }

    /** DELETE /api/admin/posts/{id}/image — remove cover image. */
    public function deleteImage(int $id)
    {
        $post = Post::findOrFail($id);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
            $post->update(['image' => null]);
        }

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug'         => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($ignoreId)],
            'title'        => ['required', 'string', 'max:255'],
            'excerpt'      => ['nullable', 'string'],
            'date'         => ['nullable', 'date'],
            'reading_time' => ['nullable', 'integer', 'min:0'],
            'sections'     => ['nullable', 'array'],
            'is_published' => ['boolean'],
        ]);
    }
}
