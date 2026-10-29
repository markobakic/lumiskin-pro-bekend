<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * POST /api/discount/validate  { code }
     * Returns whether the code exists and is still usable.
     */
    public function validate(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:20']]);

        $code = strtoupper(trim($request->input('code')));
        $row  = DiscountCode::where('code', $code)->first();

        if (! $row) {
            return response()->json(['valid' => false, 'reason' => 'not_found']);
        }

        if ($row->is_used) {
            return response()->json(['valid' => false, 'reason' => 'already_used']);
        }

        return response()->json(['valid' => true, 'discount' => 10]);
    }

    /**
     * POST /api/discount/use  { code }
     * Marks the code as used. Called when the order is confirmed.
     */
    public function use(Request $request)
    {
        $request->validate(['code' => ['required', 'string', 'max:20']]);

        $code = strtoupper(trim($request->input('code')));
        $row  = DiscountCode::where('code', $code)->where('is_used', false)->first();

        if (! $row) {
            return response()->json(['ok' => false, 'reason' => 'not_found_or_used'], 422);
        }

        $row->update(['is_used' => true, 'used_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
