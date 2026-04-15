<?php

namespace Tests\Feature\Api;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_ticket_with_attachment(): void
    {
        Storage::fake('public');

        $response = $this->post(
            route('api.tickets.store'),
            [
                'name' => 'Olena Koval',
                'phone' => '+380501234567',
                'email' => 'olena@example.com',
                'subject' => 'Потрібна консультація',
                'message' => 'Деталі по майбутньому проекту.',
                'attachment' => UploadedFile::fake()->create('brief.pdf', 256, 'application/pdf'),
            ],
            ['Accept' => 'application/json'],
        );

        $response
            ->assertCreated()
            ->assertJsonPath('data.status', TicketStatus::New->value)
            ->assertJsonPath('data.client.email', 'olena@example.com');

        $this->assertDatabaseHas('customers', [
            'email' => 'olena@example.com',
            'phone' => '+380501234567',
        ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Потрібна консультація',
            'status' => TicketStatus::New->value,
        ]);

        $ticket = Ticket::query()->with('media')->firstOrFail();

        $this->assertCount(1, $ticket->getMedia('attachments'));
    }

    public function test_it_rejects_a_second_ticket_for_the_same_contact_on_the_same_day(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'olena@example.com',
            'phone' => '+380501234567',
        ]);

        Ticket::factory()
            ->state([
                'client_id' => $customer->id,
                'created_at' => now(),
                'status' => TicketStatus::New,
            ])
            ->create();

        $response = $this->post(
            route('api.tickets.store'),
            [
                'name' => 'Olena Koval',
                'phone' => '+380501234567',
                'email' => 'olena@example.com',
                'subject' => 'Ще одна заявка',
                'message' => 'Повторне звернення в ту саму добу.',
            ],
            ['Accept' => 'application/json'],
        );

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'phone']);
    }
}
