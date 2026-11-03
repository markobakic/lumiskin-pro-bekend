<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends Controller
{
    /**
     * POST /api/admin/products/media/image
     * Upload jedne slike proizvoda → vraća { url }.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $path = $request->file('file')->store('products/images', 'public');

        return response()->json(['url' => asset('storage/' . $path)]);
    }

    /**
     * POST /api/admin/products/media/video
     * Upload videa proizvoda → vraća { url }.
     */
    public function uploadVideo(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/webm,video/quicktime',
                'max:102400', // 100 MB
            ],
        ]);

        $path = $request->file('file')->store('products/videos', 'public');

        return response()->json(['url' => asset('storage/' . $path)]);
    }
}
