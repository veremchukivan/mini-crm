<?php

namespace App\Data;

readonly class TicketFilterData
{
    public function __construct(
        public ?string $status,
        public ?string $dateFrom,
        public ?string $dateTo,
        public ?string $email,
        public ?string $phone,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: self::normalize($data['status'] ?? null),
            dateFrom: self::normalize($data['date_from'] ?? null),
            dateTo: self::normalize($data['date_to'] ?? null),
            email: self::normalize($data['email'] ?? null),
            phone: self::normalize($data['phone'] ?? null),
        );
    }

    private static function normalize(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmedValue = trim($value);

        return $trimmedValue !== '' ? $trimmedValue : null;
    }
}
