<?php

namespace App\Http\Controllers\Api;

use App\Actions\Tickets\BuildTicketStatisticsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\TicketStatisticsResource;
use OpenApi\Attributes as OA;

class TicketStatisticsController extends Controller
{
    #[OA\Get(
        path: '/api/tickets/statistics',
        operationId: 'ticketStatistics',
        description: 'Returns ticket counters for the current day, week and month.',
        summary: 'Get ticket statistics',
        tags: ['Statistics'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistics payload',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'day', type: 'integer', example: 4),
                                new OA\Property(property: 'week', type: 'integer', example: 17),
                                new OA\Property(property: 'month', type: 'integer', example: 54),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    public function __invoke(BuildTicketStatisticsAction $buildTicketStatistics): TicketStatisticsResource
    {
        return TicketStatisticsResource::make($buildTicketStatistics->execute());
    }
}
