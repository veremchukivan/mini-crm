<?php

namespace App\Http\Controllers\Api;

use App\Actions\Tickets\CreateTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TicketController extends Controller
{
    #[OA\Post(
        path: '/api/tickets',
        operationId: 'storeTicket',
        description: 'Creates a new CRM ticket from the public widget and optionally stores one attachment.',
        summary: 'Create ticket',
        tags: ['Tickets'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['name', 'phone', 'email', 'subject', 'message'],
                    properties: [
                        new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Olena Koval'),
                        new OA\Property(property: 'phone', type: 'string', pattern: '^\\+[1-9]\\d{1,14}$', example: '+380501234567'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'olena@example.com'),
                        new OA\Property(property: 'subject', type: 'string', maxLength: 255, example: 'Потрібна консультація'),
                        new OA\Property(property: 'message', type: 'string', example: 'Опишіть ваше завдання або запит.'),
                        new OA\Property(property: 'attachment', type: 'string', format: 'binary', nullable: true),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Ticket created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'subject', type: 'string', example: 'Потрібна консультація'),
                                new OA\Property(property: 'message', type: 'string'),
                                new OA\Property(property: 'status', type: 'string', example: 'new'),
                                new OA\Property(property: 'status_label', type: 'string', example: 'Новий'),
                                new OA\Property(property: 'manager_responded_at', type: 'string', format: 'date-time', nullable: true),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(
                                    property: 'client',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Olena Koval'),
                                        new OA\Property(property: 'phone', type: 'string', example: '+380501234567'),
                                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'olena@example.com'),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: 'object'
                        ),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error or daily limit exceeded'
            ),
        ]
    )]
    public function __invoke(StoreTicketRequest $request, CreateTicketAction $createTicket): JsonResponse
    {
        $ticket = $createTicket->execute(
            $request->ticketData(),
            $request->file('attachment'),
        );

        return TicketResource::make($ticket)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
