<?php

namespace Tests\Feature;

use App\Models\FnbOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FnbMidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_webhook_marks_fnb_order_as_paid(): void
    {
        config(['services.midtrans.server_key' => 'test-server-key']);

        $user = $this->user();
        $order = FnbOrder::create([
            'user_id' => $user->id,
            'midtrans_order_id' => 'FNB-test-123',
            'subtotal' => 50000,
            'tax' => 5000,
            'total' => 55000,
            'status' => 'pending',
            'items' => [
                ['menu_id' => 1, 'name' => 'Nasi Goreng', 'quantity' => 1, 'price' => 50000],
            ],
        ]);

        $payload = $this->signedMidtransPayload([
            'transaction_id' => 'trx-123',
            'order_id' => $order->midtrans_order_id,
            'gross_amount' => '55000.00',
            'status_code' => '200',
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ]);

        $this->postJson('/api/webhook/midtrans', $payload)
            ->assertOk()
            ->assertJsonPath('message', 'Webhook processed successfully');

        $this->assertDatabaseHas('fnb_orders', [
            'id' => $order->id,
            'status' => 'paid',
            'payment_method' => 'qris',
            'midtrans_transaction_id' => 'trx-123',
        ]);

        $this->assertNotNull($order->fresh()->paid_at);
    }

    public function test_user_can_poll_their_fnb_payment_status(): void
    {
        $user = $this->user();
        $order = FnbOrder::create([
            'user_id' => $user->id,
            'midtrans_order_id' => 'FNB-test-456',
            'subtotal' => 30000,
            'tax' => 3000,
            'total' => 33000,
            'status' => 'paid',
            'payment_method' => 'gopay',
            'paid_at' => now(),
        ]);

        $this->actingAs($user)
            ->getJson(route('user.fnb.payment-status', ['orderId' => $order->midtrans_order_id]))
            ->assertOk()
            ->assertJsonPath('status', 'paid')
            ->assertJsonPath('payment_method', 'gopay');
    }

    public function test_user_cannot_poll_another_users_fnb_payment_status(): void
    {
        $owner = $this->user('owner@example.com', 'owner');
        $otherUser = $this->user('other@example.com', 'other');
        $order = FnbOrder::create([
            'user_id' => $owner->id,
            'midtrans_order_id' => 'FNB-test-789',
            'subtotal' => 30000,
            'tax' => 3000,
            'total' => 33000,
            'status' => 'paid',
        ]);

        $this->actingAs($otherUser)
            ->getJson(route('user.fnb.payment-status', ['orderId' => $order->midtrans_order_id]))
            ->assertNotFound();
    }

    private function signedMidtransPayload(array $payload): array
    {
        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . config('services.midtrans.server_key')
        );

        return $payload;
    }

    private function user(string $email = 'raka@example.com', string $username = 'raka'): User
    {
        return User::create([
            'name' => 'Raka',
            'username' => $username,
            'email' => $email,
            'phone' => '08123456789',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
