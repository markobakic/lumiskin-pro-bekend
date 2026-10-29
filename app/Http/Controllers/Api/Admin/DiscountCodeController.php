<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;

class DiscountCodeController extends Controller
{
    /** GET /api/admin/discount-codes — all codes, newest first. */
    public function index()
    {
        return DiscountCode::orderByDesc('created_at')->get();
    }
}
