<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    /** GET /api/admin/comments — svi komentari sa info o postu. */
    public function index()
    {
        return Comment::with('post:id,title,slug')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($c) => [
                'id'          => $c->id,
                'post_id'     => $c->post_id,
                'post_title'  => $c->post?->title,
                'post_slug'   => $c->post?->slug,
                'author_name' => $c->author_name,
                'body'        => $c->body,
                'is_approved' => $c->is_approved,
                'created_at'  => $c->created_at->format('Y-m-d H:i'),
            ]);
    }

    /** PATCH /api/admin/comments/{id}/approve */
    public function approve(int $id)
    {
        Comment::findOrFail($id)->update(['is_approved' => true]);

        return response()->json(['ok' => true]);
    }

    /** PATCH /api/admin/comments/{id}/reject */
    public function reject(int $id)
    {
        Comment::findOrFail($id)->update(['is_approved' => false]);

        return response()->json(['ok' => true]);
    }

    /** DELETE /api/admin/comments/{id} */
    public function destroy(int $id)
    {
        Comment::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
