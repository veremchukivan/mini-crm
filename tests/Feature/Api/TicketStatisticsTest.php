<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TicketStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_ticket_statistics_for_day_week_and_month(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-04-15 10:00:00'));

        $customer = Customer::factory()->create();

        Ticket::factory()->count(2)->state([
            'client_id' => $customer->id,
            'created_at' => Carbon::now()->subHours(2),
        ])->create();

        Ticket::factory()->count(2)->state([
            'client_id' => $customer->id,
            'created_at' => Carbon::parse('2026-04-14 12:00:00'),
        ])->create();

        Ticket::factory()->state([
            'client_id' => $customer->id,
            'created_at' => Carbon::parse('2026-04-03 12:00:00'),
        ])->create();

        Ticket::factory()->state([
            'client_id' => $customer->id,
            'created_at' => Carbon::parse('2026-03-29 12:00:00'),
        ])->create();

        $response = $this->getJson(route('api.tickets.statistics'));

        $response
            ->assertOk()
            ->assertJson([
                'data' => [
                    'day' => 2,
                    'week' => 4,
                    'month' => 5,
                ],
            ]);

        Carbon::setTestNow();
    }
}
