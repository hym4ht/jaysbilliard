<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BookingValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_user_cannot_book_a_past_date(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-14 16:00:00', 'Asia/Jakarta'));

        $response = $this->actingAs($this->user())->postJson(route('booking.store'), [
            'table_ids' => [$this->table()->id],
            'customer_name' => 'Raka',
            'phone' => '08123456789',
            'booking_date' => '2026-05-13',
            'start_time' => '18:00',
            'end_time' => '19:00',
            'total_price' => 100000,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['start_time']);
    }

    public function test_user_cannot_book_a_past_time_today(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-14 16:00:00', 'Asia/Jakarta'));

        $response = $this->actingAs($this->user())->postJson(route('booking.store'), [
            'table_ids' => [$this->table()->id],
            'customer_name' => 'Raka',
            'phone' => '08123456789',
            'booking_date' => '2026-05-14',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'total_price' => 100000,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['start_time']);
    }

    public function test_user_cannot_book_an_overlapping_slot(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-05-14 16:00:00', 'Asia/Jakarta'));

        $table = $this->table();
        Booking::create([
            'table_id' => $table->id,
            'customer_name' => 'Existing Customer',
            'phone' => '08123456789',
            'booking_date' => '2026-05-15',
            'start_time' => '18:00:00',
            'end_time' => '20:00:00',
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user())->postJson(route('booking.store'), [
            'table_ids' => [$table->id],
            'customer_name' => 'Raka',
            'phone' => '08123456789',
            'booking_date' => '2026-05-15',
            'start_time' => '19:00',
            'end_time' => '21:00',
            'total_price' => 100000,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['table_ids']);
    }

    private function user(): User
    {
        return User::create([
            'name' => 'Raka',
            'username' => 'raka',
            'email' => 'raka@example.com',
            'phone' => '08123456789',
            'password' => Hash::make('password'),
        ]);
    }

    private function table(): Table
    {
        return Table::create([
            'name' => 'Meja 1',
            'type' => 'regular',
            'price_per_hour' => 100000,
            'capacity' => 4,
            'status' => 'active',
            'is_available' => true,
        ]);
    }
}
