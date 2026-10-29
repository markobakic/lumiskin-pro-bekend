<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\DB;

class NewsletterController extends Controller
{
    /** GET /api/admin/newsletter — subscribers with discount code status. */
    public function index()
    {
        return NewsletterSubscriber::query()
            ->select(
                'newsletter_subscribers.id',
                'newsletter_subscribers.email',
                'newsletter_subscribers.created_at',
                'discount_codes.code',
                'discount_codes.is_used',
                'discount_codes.used_at',
            )
            ->leftJoin('discount_codes', 'discount_codes.email', '=', 'newsletter_subscribers.email')
            ->orderByDesc('newsletter_subscribers.created_at')
            ->get();
    }
}
