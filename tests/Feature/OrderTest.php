<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private function product(string $slug, int $price, bool $active = true): Product
    {
        return Product::create([
            'slug' => $slug, 'name' => strtoupper($slug), 'price' => $price, 'is_active' => $active,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'first_name'  => 'Ana',
            'last_name'   => 'Anić',
            'email'       => 'Ana@Example.com',
            'phone'       => '0601234567',
            'address'     => 'Ulica 1',
            'postal_code' => '11000',
            'city'        => 'Beograd',
            'items'       => [['id' => 'maska', 'qty' => 2]],
        ], $overrides);
    }

    public function test_creates_order_with_prices_from_database(): void
    {
        $this->product('maska', 14990);
        $this->product('kapa', 27990);

        $res = $this->postJson('/api/orders', $this->payload([
            'items' => [
                ['id' => 'maska', 'qty' => 2, 'price' => 1],   // cena iz browsera se ignoriše
                ['id' => 'kapa', 'qty' => 1],
                ['id' => 'maska', 'qty' => 1],                 // duplikat se spaja
            ],
        ]));

        $res->assertCreated()
            ->assertJsonPath('status', 'new')
            ->assertJsonPath('subtotal', 14990 * 3 + 27990)
            ->assertJsonPath('discount', 0)
            ->assertJsonPath('total', 14990 * 3 + 27990)
            ->assertJsonPath('email', 'ana@example.com')
            ->assertJsonCount(2, 'items')
            ->assertJsonMissingPath('note');

        $order = Order::first();
        $this->assertSame(Order::formatNumber($order->id), $res->json('number'));
        $this->assertSame(3, $order->items()->where('product_slug', 'maska')->value('quantity'));
    }

    public function test_applies_and_consumes_discount_code(): void
    {
        $this->product('maska', 10000);
        DiscountCode::create(['code' => 'ABCDE', 'email' => 'ana@example.com']);

        $this->postJson('/api/orders', $this->payload(['discount_code' => 'abcde']))
            ->assertCreated()
            ->assertJsonPath('subtotal', 20000)
            ->assertJsonPath('discount', 2000)
            ->assertJsonPath('total', 18000)
            ->assertJsonPath('discountCode', 'ABCDE');

        $this->assertTrue(DiscountCode::first()->is_used);

        // drugi put isti kod ne prolazi i ne pravi porudžbinu
        $this->postJson('/api/orders', $this->payload(['discount_code' => 'ABCDE']))
            ->assertStatus(422)
            ->assertJsonValidationErrors('discount_code');
        $this->assertSame(1, Order::count());
    }

    public function test_rejects_expired_or_foreign_discount_code(): void
    {
        $this->product('maska', 10000);
        $expired = DiscountCode::create(['code' => 'OLD11', 'email' => 'ana@example.com']);
        $expired->forceFill(['created_at' => now()->subMinutes(16)])->save();
        DiscountCode::create(['code' => 'OTHER', 'email' => 'neko@drugi.com']);

        $this->postJson('/api/orders', $this->payload(['discount_code' => 'OLD11']))->assertStatus(422);
        $this->postJson('/api/orders', $this->payload(['discount_code' => 'OTHER']))->assertStatus(422);
        $this->assertSame(0, Order::count());
    }

    public function test_rejects_unknown_or_inactive_products(): void
    {
        $this->product('skrivena', 5000, active: false);

        $this->postJson('/api/orders', $this->payload(['items' => [['id' => 'skrivena', 'qty' => 1]]]))
            ->assertStatus(422)->assertJsonValidationErrors('items');
        $this->postJson('/api/orders', $this->payload(['items' => [['id' => 'nema', 'qty' => 1]]]))
            ->assertStatus(422);
        $this->postJson('/api/orders', $this->payload(['items' => []]))
            ->assertStatus(422);
        $this->assertSame(0, Order::count());
    }

    public function test_validates_customer_fields(): void
    {
        $this->product('maska', 10000);

        $this->postJson('/api/orders', $this->payload(['first_name' => '', 'email' => 'nije-email']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'email']);
    }

    public function test_admin_endpoints_require_auth(): void
    {
        $this->getJson('/api/admin/orders')->assertUnauthorized();
    }

    public function test_admin_can_list_update_and_delete_orders(): void
    {
        $this->product('maska', 10000);
        $id = $this->postJson('/api/orders', $this->payload())->json('id');

        Sanctum::actingAs(User::create([
            'name' => 'Admin', 'username' => 'admin', 'email' => 'a@a.rs', 'password' => 'secret123',
        ]));

        $this->getJson('/api/admin/orders')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.id', $id)
            ->assertJsonPath('0.items.0.name', 'MASKA');

        $this->patchJson("/api/admin/orders/$id", ['status' => 'shipped', 'note' => 'Poslato BEX-om'])
            ->assertOk()
            ->assertJsonPath('status', 'shipped')
            ->assertJsonPath('note', 'Poslato BEX-om');

        $this->patchJson("/api/admin/orders/$id", ['status' => 'izmisljen'])->assertStatus(422);

        $this->getJson('/api/admin/orders?status=new')->assertOk()->assertJsonCount(0);
        $this->getJson('/api/admin/orders?status=shipped')->assertOk()->assertJsonCount(1);

        $this->deleteJson("/api/admin/orders/$id")->assertOk();
        $this->assertSame(0, Order::count());
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_deleting_product_keeps_order_item_snapshot(): void
    {
        $product = $this->product('maska', 10000);
        $this->postJson('/api/orders', $this->payload())->assertCreated();

        $product->delete();

        $item = Order::first()->items()->first();
        $this->assertNull($item->product_id);
        $this->assertSame('MASKA', $item->product_name);
        $this->assertSame(10000, $item->unit_price);
    }
}
