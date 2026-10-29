<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;

class PostController extends Controller
{
    /** GET /api/posts — published posts, newest first. */
    public function index()
    {
        $posts = Post::query()
            ->where('is_published', true)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return PostResource::collection($posts);
    }

    /** GET /api/posts/{slug} — single published post. 404 if missing. */
    public function show(string $slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return new PostResource($post);
    }
}
