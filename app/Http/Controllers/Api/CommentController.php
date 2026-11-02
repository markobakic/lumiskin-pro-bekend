<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /** GET /api/posts/{slug}/comments — odobreni komentari za jedan post. */
    public function index(string $slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $comments = $post->comments()
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->get(['id', 'author_name', 'body', 'created_at'])
            ->map(fn ($c) => [
                'id'          => $c->id,
                'author_name' => $c->author_name,
                'body'        => $c->body,
                'created_at'  => $c->created_at->format('Y-m-d H:i'),
            ]);

        return response()->json($comments);
    }

    /** POST /api/posts/{slug}/comments — pošalji komentar (čeka odobravanje). */
    public function store(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'body'        => ['required', 'string', 'max:1000'],
        ]);

        $post->comments()->create([
            'author_name' => $data['author_name'],
            'body'        => $data['body'],
            'is_approved' => false,
        ]);

        return response()->json([
            'ok'      => true,
            'message' => 'Komentar je primljen i čeka odobravanje.',
        ], 201);
    }
}
