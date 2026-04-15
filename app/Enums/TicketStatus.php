<?php

namespace App\Enums;

enum TicketStatus: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Processed = 'processed';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::New->value => 'Новий',
            self::InProgress->value => 'В роботі',
            self::Processed->value => 'Оброблений',
        ];
    }

    public function label(): string
    {
        return self::options()[$this->value];
    }
}
