<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * POST /api/newsletter/subscribe  { email, website? }
     *
     * - `website` is a honeypot: silently succeed if filled.
     * - If email already exists → return alreadySubscribed: true (no code).
     * - Otherwise → create subscriber, generate unique 5-char code, save to
     *   discount_codes, return the code so the frontend can email it.
     */
    public function subscribe(Request $request)
    {
        if ($request->filled('website')) {
            return response()->json(['ok' => true, 'alreadySubscribed' => false]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:254'],
        ], [
            'email.required' => 'Email je obavezan.',
            'email.email'    => 'Upiši ispravnu email adresu.',
        ]);

        $email = strtolower(trim($validated['email']));

        if (NewsletterSubscriber::where('email', $email)->exists()) {
            return response()->json([
                'ok'               => true,
                'alreadySubscribed' => true,
            ]);
        }

        NewsletterSubscriber::create(['email' => $email]);

        $code = $this->generateUniqueCode();
        DiscountCode::create(['code' => $code, 'email' => $email]);

        return response()->json([
            'ok'               => true,
            'alreadySubscribed' => false,
            'code'             => $code,
        ], 201);
    }

    private function generateUniqueCode(): string
    {
        do {
            $code = $this->randomCode();
        } while (DiscountCode::where('code', $code)->exists());

        return $code;
    }

    private function randomCode(int $length = 5): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $max   = strlen($chars) - 1;
        $out   = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $chars[random_int(0, $max)];
        }

        return $out;
    }
}
