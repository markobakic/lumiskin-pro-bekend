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

        $reason = DiscountCode::rejectionReason(
            DiscountCode::findFor($request->input('code'), $request->input('email'))
        );

        if ($reason) {
            return response()->json(['valid' => false, 'reason' => $reason]);
        }

        return response()->json(['valid' => true, 'discount' => DiscountCode::PERCENT]);
    }

    /**
     * POST /api/discount/use  { code, email }
     * Marks the code as used. (Porudžbine kroz POST /api/orders troše kod same.)
     */
    public function use(Request $request)
    {
        $request->validate([
            'code'  => ['required', 'string', 'max:20'],
            'email' => ['required', 'email:rfc', 'max:254'],
        ]);

        $row = DiscountCode::findFor($request->input('code'), $request->input('email'));
        $reason = DiscountCode::rejectionReason($row);

        if ($reason === 'not_found' || $reason === 'already_used') {
            return response()->json(['ok' => false, 'reason' => 'not_found_or_used'], 422);
        }
        if ($reason === 'expired') {
            return response()->json(['ok' => false, 'reason' => 'expired'], 422);
        }

        $row->update(['is_used' => true, 'used_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
