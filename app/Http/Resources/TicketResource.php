<?php

namespace App\Http\Resources;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Ticket
 */
class TicketResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'message' => $this->message,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'manager_responded_at' => $this->manager_responded_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'client' => [
                'id' => $this->client?->id,
                'name' => $this->client?->name,
                'phone' => $this->client?->phone,
                'email' => $this->client?->email,
            ],
            'attachments' => $this->whenLoaded('media', function (): array {
                return $this->getMedia('attachments')
                    ->map(fn ($media) => [
                        'id' => $media->id,
                        'name' => $media->name,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                    ])
                    ->values()
                    ->all();
            }),
        ];
    }
}
