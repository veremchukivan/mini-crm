<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Mini CRM API',
    description: 'API for creating tickets from the embeddable widget and reading ticket statistics.'
)]
#[OA\Server(
    url: '/',
    description: 'Application root'
)]
#[OA\Tag(
    name: 'Tickets',
    description: 'Public endpoints for widget ticket flow'
)]
#[OA\Tag(
    name: 'Statistics',
    description: 'Ticket statistics endpoints'
)]
class OpenApiSpecification {}
