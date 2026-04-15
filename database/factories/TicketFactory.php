<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Customer::factory(),
            'subject' => fake()->sentence(4),
            'message' => fake()->paragraph(3),
            'status' => fake()->randomElement([
                TicketStatus::New,
                TicketStatus::InProgress,
                TicketStatus::Processed,
            ]),
            'manager_responded_at' => fake()->boolean(45) ? fake()->dateTimeBetween('-2 weeks', 'now') : null,
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function asNew(): static
    {
        return $this->state(fn () => [
            'status' => TicketStatus::New,
            'manager_responded_at' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => [
            'status' => TicketStatus::InProgress,
            'manager_responded_at' => null,
        ]);
    }

    public function processed(): static
    {
        return $this->state(fn () => [
            'status' => TicketStatus::Processed,
            'manager_responded_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}
