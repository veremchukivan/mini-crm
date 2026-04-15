<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTicketManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_panel(): void
    {
        $this->get(route('admin.tickets.index'))
            ->assertRedirect(route('login'));
    }

    public function test_manager_can_filter_tickets(): void
    {
        Carbon::setTestNow('2026-04-15 09:00:00');

        $manager = $this->createManager();
        $matchingCustomer = Customer::factory()->create([
            'email' => 'olena@example.com',
            'phone' => '+380501234567',
        ]);

        $otherCustomer = Customer::factory()->create([
            'email' => 'other@example.com',
            'phone' => '+380509999999',
        ]);

        Ticket::factory()->state([
            'client_id' => $matchingCustomer->id,
            'subject' => 'Matching subject',
            'status' => TicketStatus::New,
            'created_at' => now(),
        ])->create();

        Ticket::factory()->state([
            'client_id' => $otherCustomer->id,
            'subject' => 'Other subject',
            'status' => TicketStatus::Processed,
            'created_at' => now()->subMonth(),
        ])->create();

        $response = $this
            ->actingAs($manager)
            ->get(route('admin.tickets.index', [
                'status' => TicketStatus::New->value,
                'email' => 'olena@example.com',
                'phone' => '+380501234567',
                'date_from' => '2026-04-15',
                'date_to' => '2026-04-15',
            ]));

        $response
            ->assertOk()
            ->assertSee('Matching subject')
            ->assertDontSee('Other subject');

        Carbon::setTestNow();
    }

    public function test_manager_can_update_ticket_status(): void
    {
        $manager = $this->createManager();
        $ticket = Ticket::factory()->create();

        $this->actingAs($manager)
            ->patch(route('admin.tickets.update', $ticket), [
                'status' => TicketStatus::Processed->value,
            ])
            ->assertRedirect(route('admin.tickets.show', $ticket));

        $ticket->refresh();

        $this->assertSame(TicketStatus::Processed, $ticket->status);
        $this->assertNotNull($ticket->manager_responded_at);
    }

    public function test_non_manager_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.tickets.index'))
            ->assertForbidden();
    }

    public function test_manager_can_download_ticket_attachment(): void
    {
        Storage::fake('public');

        $manager = $this->createManager();
        $ticket = Ticket::factory()->create();

        $ticket
            ->addMedia(UploadedFile::fake()->create('note.txt', 12, 'text/plain'))
            ->toMediaCollection('attachments');

        $media = $ticket->getFirstMedia('attachments');

        $this->actingAs($manager)
            ->get(route('admin.tickets.download', [$ticket, $media->id]))
            ->assertOk()
            ->assertHeader('content-disposition');
    }

    private function createManager(): User
    {
        Role::findOrCreate('manager', 'web');

        $user = User::factory()->create();
        $user->assignRole('manager');

        return $user;
    }
}
