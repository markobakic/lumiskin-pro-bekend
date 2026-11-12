<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /** GET /api/admin/orders?status=new — sve porudžbine, najnovije prve. */
    public function index(Request $request)
    {
        $request->validate([
            'status' => ['nullable', Rule::in(Order::STATUSES)],
        ]);

        $orders = Order::query()
            ->with('items')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return OrderResource::collection($orders);
    }

    /** GET /api/admin/orders/{id} */
    public function show(int $id)
    {
        return new OrderResource(Order::with('items')->findOrFail($id));
    }

    /** PATCH /api/admin/orders/{id}  { status?, note? } */
    public function update(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $data = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(Order::STATUSES)],
            'note'   => ['sometimes', 'nullable', 'string', 'max:5000'],
        ]);

        $order->update($data);

        return new OrderResource($order->load('items'));
    }

    /** DELETE /api/admin/orders/{id} */
    public function destroy(int $id)
    {
        Order::findOrFail($id)->delete();

        return response()->json(['ok' => true]);
    }
}
