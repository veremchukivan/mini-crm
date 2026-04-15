<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketStatisticsResource extends JsonResource
{
    /**
     * @return array<string, int>
     */
    public function toArray(Request $request): array
    {
        return [
            'day' => (int) $this['day'],
            'week' => (int) $this['week'],
            'month' => (int) $this['month'],
        ];
    }
}
