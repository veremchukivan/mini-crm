<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::query()->get();

        foreach ($customers as $index => $customer) {
            $tickets = Ticket::factory()
                ->count(2)
                ->state(['client_id' => $customer->id])
                ->create();

            if ($index > 1) {
                continue;
            }

            foreach ($tickets as $ticket) {
                $ticket
                    ->addMedia(database_path('seeders/files/sample-attachment.txt'))
                    ->preservingOriginal()
                    ->toMediaCollection('attachments');
            }
        }
    }
}
