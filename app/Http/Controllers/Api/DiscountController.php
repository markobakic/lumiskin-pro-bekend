<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * POST /api/discount/validate  { code, email }
     * Returns whether the code exists, belongs to the email and is still usable.
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'  => ['required', 'string', 'max:20'],
            'email' => ['required', 'email:rfc', 'max:254'],
        ]);

        $code  = strtoupper(trim($request->input('code')));
        $email = strtolower(trim($request->input('email')));
        $row   = DiscountCode::where('code', $code)->where('email', $email)->first();

        if (! $row) {
            return response()->json(['valid' => false, 'reason' => 'not_found']);
        }

        if ($row->is_used) {
            return response()->json(['valid' => false, 'reason' => 'already_used']);
        }

        // Kod važi 15 minuta od generisanja
        if ($row->created_at->addMinutes(15)->isPast()) {
            return response()->json(['valid' => false, 'reason' => 'expired']);
        }

        return response()->json(['valid' => true, 'discount' => 10]);
    }

    /**
     * POST /api/discount/use  { code, email }
     * Marks the code as used. Called when the order is confirmed.
     */
    public function use(Request $request)
    {
        $request->validate([
            'code'  => ['required', 'string', 'max:20'],
            'email' => ['required', 'email:rfc', 'max:254'],
        ]);

        $code  = strtoupper(trim($request->input('code')));
        $email = strtolower(trim($request->input('email')));
        $row   = DiscountCode::where('code', $code)
            ->where('email', $email)
            ->where('is_used', false)
            ->first();

        if (! $row) {
            return response()->json(['ok' => false, 'reason' => 'not_found_or_used'], 422);
        }

        // Spreči upotrebu isteklog koda (15 minuta od generisanja)
        if ($row->created_at->addMinutes(15)->isPast()) {
            return response()->json(['ok' => false, 'reason' => 'expired'], 422);
        }

        $row->update(['is_used' => true, 'used_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
