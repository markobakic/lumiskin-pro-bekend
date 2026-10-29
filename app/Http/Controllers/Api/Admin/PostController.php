<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /** GET /api/admin/posts — all posts (incl. unpublished). */
    public function index()
    {
        return Post::orderByDesc('date')->orderByDesc('id')->get();
    }

    /** GET /api/admin/posts/{id} */
    public function show(int $id)
    {
        return Post::findOrFail($id);
    }

    /** POST /api/admin/posts */
    public function store(Request $request)
    {
        $post = Post::create($this->validateData($request));

        return response()->json($post, 201);
    }

    /** PUT /api/admin/posts/{id} */
    public function update(Request $request, int $id)
    {
        $post = Post::findOrFail($id);
        $post->update($this->validateData($request, $post->id));

        return response()->json($post);
    }

    /** DELETE /api/admin/posts/{id} */
    public function destroy(int $id)
    {
        Post::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'slug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($ignoreId)],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
            'reading_time' => ['nullable', 'integer', 'min:0'],
            'sections' => ['nullable', 'array'],
            'is_published' => ['boolean'],
        ]);
    }
}
