<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;

class BuildTicketStatisticsAction
{
    /**
     * @return array{day: int, week: int, month: int}
     */
    public function execute(): array
    {
        return [
            'day' => Ticket::query()->createdToday()->count(),
            'week' => Ticket::query()->createdThisWeek()->count(),
            'month' => Ticket::query()->createdThisMonth()->count(),
        ];
    }
}
