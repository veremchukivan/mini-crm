<?php

namespace App\Actions\Tickets;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateTicketAction
{
    /**
     * @param  array{name: string, phone: string, email: string, subject: string, message: string}  $payload
     */
    public function execute(array $payload, ?UploadedFile $attachment = null): Ticket
    {
        $normalizedPayload = [
            ...$payload,
            'email' => Str::lower(trim($payload['email'])),
            'phone' => preg_replace('/\s+/', '', trim($payload['phone'])) ?: trim($payload['phone']),
        ];

        [$customerByEmail, $customerByPhone] = $this->resolveCustomers(
            $normalizedPayload['email'],
            $normalizedPayload['phone'],
        );

        if ($customerByEmail !== null && $customerByPhone !== null && ! $customerByEmail->is($customerByPhone)) {
            throw ValidationException::withMessages([
                'email' => 'Email вже прив’язаний до іншого клієнта.',
                'phone' => 'Номер телефону вже прив’язаний до іншого клієнта.',
            ]);
        }

        $customer = $customerByEmail ?? $customerByPhone ?? new Customer;

        $customer->fill([
            'name' => $normalizedPayload['name'],
            'email' => $normalizedPayload['email'],
            'phone' => $normalizedPayload['phone'],
        ]);

        $this->ensureDailyLimitNotExceeded($customer);

        /** @var Ticket $ticket */
        $ticket = DB::transaction(function () use ($customer, $normalizedPayload, $attachment): Ticket {
            $customer->save();

            $ticket = $customer->tickets()->create([
                'subject' => $normalizedPayload['subject'],
                'message' => $normalizedPayload['message'],
                'status' => TicketStatus::New,
                'manager_responded_at' => null,
            ]);

            if ($attachment instanceof UploadedFile) {
                $ticket
                    ->addMedia($attachment)
                    ->toMediaCollection('attachments');
            }

            return $ticket;
        });

        return $ticket->load(['client', 'media']);
    }

    private function ensureDailyLimitNotExceeded(Customer $customer): void
    {
        if (! $customer->exists) {
            return;
        }

        $hasTicketToday = $customer->tickets()
            ->createdToday()
            ->exists();

        if (! $hasTicketToday) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => 'Не більше однієї заявки на добу з одного email або номера телефону.',
            'phone' => 'Не більше однієї заявки на добу з одного email або номера телефону.',
        ]);
    }

    /**
     * @return array{0: Customer|null, 1: Customer|null}
     */
    private function resolveCustomers(string $email, string $phone): array
    {
        return [
            Customer::query()->where('email', $email)->first(),
            Customer::query()->where('phone', $phone)->first(),
        ];
    }
}
