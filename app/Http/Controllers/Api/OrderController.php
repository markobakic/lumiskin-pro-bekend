<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * POST /api/orders
     * {
     *   first_name, last_name, email, phone, address, postal_code, city,
     *   items: [{ id: <product slug>, qty }],
     *   discount_code?: string,
     *   website?: honeypot
     * }
     *
     * Cene i ukupan iznos se računaju ovde iz baze — cene koje šalje browser se ignorišu.
     * Kupon se proverava i troši u istoj transakciji (ne može da se iskoristi dvaput).
     */
    public function store(Request $request)
    {
        if ($request->filled('website')) {
            // honeypot — botu glumimo uspeh, ništa ne čuvamo
            return response()->json(['ok' => true], 201);
        }

        $data = $request->validate([
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email:rfc', 'max:254'],
            'phone'         => ['required', 'string', 'max:40'],
            'address'       => ['required', 'string', 'max:255'],
            'postal_code'   => ['required', 'string', 'max:20'],
            'city'          => ['required', 'string', 'max:100'],
            'items'         => ['required', 'array', 'min:1', 'max:50'],
            'items.*.id'    => ['required', 'string', 'max:255'],
            'items.*.qty'   => ['required', 'integer', 'min:1', 'max:99'],
            'discount_code' => ['nullable', 'string', 'max:20'],
        ], [
            'items.required' => 'Korpa je prazna.',
            'items.min'      => 'Korpa je prazna.',
            'email.email'    => 'Upiši ispravnu email adresu.',
        ]);

        // Spoji iste proizvode (ako je browser poslao duplikate)
        $qtyBySlug = [];
        foreach ($data['items'] as $item) {
            $qtyBySlug[$item['id']] = min(99, ($qtyBySlug[$item['id']] ?? 0) + (int) $item['qty']);
        }

        $products = Product::query()
            ->whereIn('slug', array_keys($qtyBySlug))
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        $missing = array_diff(array_keys($qtyBySlug), $products->keys()->all());
        if ($missing) {
            throw ValidationException::withMessages([
                'items' => 'Neki proizvodi iz korpe više nisu dostupni. Osveži korpu i pokušaj ponovo.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $qtyBySlug, $products) {
            $lines = [];
            $subtotal = 0;
            foreach ($qtyBySlug as $slug => $qty) {
                $product = $products[$slug];
                $lineTotal = $product->price * $qty;
                $subtotal += $lineTotal;
                $lines[] = [
                    'product_id'   => $product->id,
                    'product_slug' => $product->slug,
                    'product_name' => $product->name,
                    'unit_price'   => $product->price,
                    'quantity'     => $qty,
                    'line_total'   => $lineTotal,
                ];
            }

            $email = DiscountCode::normalizeEmail($data['email']);
            $code = null;
            $percent = 0;

            if (! empty($data['discount_code'])) {
                $row = DiscountCode::where('code', DiscountCode::normalizeCode($data['discount_code']))
                    ->where('email', $email)
                    ->lockForUpdate()
                    ->first();

                $reason = DiscountCode::rejectionReason($row);
                if ($reason) {
                    throw ValidationException::withMessages([
                        'discount_code' => match ($reason) {
                            'already_used' => 'Ovaj kupon kod je već iskorišćen.',
                            'expired'      => 'Ovaj kupon kod je istekao.',
                            default        => 'Kod i email se ne poklapaju ili kod nije validan.',
                        },
                    ]);
                }

                $row->update(['is_used' => true, 'used_at' => now()]);
                $code = $row->code;
                $percent = DiscountCode::PERCENT;
            }

            $discount = (int) round($subtotal * $percent / 100);

            $order = Order::create([
                'status'           => 'new',
                'first_name'       => trim($data['first_name']),
                'last_name'        => trim($data['last_name']),
                'email'            => $email,
                'phone'            => trim($data['phone']),
                'address'          => trim($data['address']),
                'postal_code'      => trim($data['postal_code']),
                'city'             => trim($data['city']),
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'total'            => $subtotal - $discount,
                'discount_code'    => $code,
                'discount_percent' => $percent,
                'payment_method'   => 'cod',
            ]);
            $order->update(['number' => Order::formatNumber($order->id)]);
            $order->items()->createMany($lines);

            return $order;
        });

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(201);
    }
}
