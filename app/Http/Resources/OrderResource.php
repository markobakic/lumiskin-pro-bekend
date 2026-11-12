<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Porudžbina — isti oblik za javni odgovor posle kreiranja i za admin panel. */
class OrderResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'number'          => $this->number,
            'status'          => $this->status,
            'firstName'       => $this->first_name,
            'lastName'        => $this->last_name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'address'         => $this->address,
            'postalCode'      => $this->postal_code,
            'city'            => $this->city,
            'subtotal'        => $this->subtotal,
            'discount'        => $this->discount,
            'total'           => $this->total,
            'discountCode'    => $this->discount_code,
            'discountPercent' => $this->discount_percent,
            'paymentMethod'   => $this->payment_method,
            'items'           => $this->whenLoaded('items', fn () => $this->items->map(fn ($i) => [
                'productId' => $i->product_slug,
                'name'      => $i->product_name,
                'unitPrice' => $i->unit_price,
                'quantity'  => $i->quantity,
                'lineTotal' => $i->line_total,
            ])->values()),
            // interna napomena — samo za admin
            $this->mergeWhen($request->is('api/admin/*'), [
                'note' => $this->note,
            ]),
            'createdAt'       => $this->created_at?->toIso8601String(),
            'updatedAt'       => $this->updated_at?->toIso8601String(),
        ];
    }
}
