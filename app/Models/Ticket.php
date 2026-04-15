<?php

namespace App\Models;

use App\Data\TicketFilterData;
use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;
    use InteractsWithMedia;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'subject',
        'message',
        'status',
        'manager_responded_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'manager_responded_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Customer, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
    }

    public function scopeCreatedToday(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->whereBetween('created_at', [$now->copy()->startOfDay(), $now->copy()->endOfDay()]);
    }

    public function scopeCreatedThisWeek(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
    }

    public function scopeCreatedThisMonth(Builder $query): Builder
    {
        $now = Carbon::now();

        return $query->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]);
    }

    public function scopeFilter(Builder $query, TicketFilterData $filters): Builder
    {
        return $query
            ->when($filters->status, fn (Builder $builder) => $builder->where('status', $filters->status))
            ->when($filters->dateFrom, fn (Builder $builder) => $builder->whereDate('created_at', '>=', $filters->dateFrom))
            ->when($filters->dateTo, fn (Builder $builder) => $builder->whereDate('created_at', '<=', $filters->dateTo))
            ->when(
                $filters->email,
                fn (Builder $builder) => $builder->whereHas(
                    'client',
                    fn (Builder $customerQuery) => $customerQuery->where('email', 'like', '%'.$filters->email.'%')
                )
            )
            ->when(
                $filters->phone,
                fn (Builder $builder) => $builder->whereHas(
                    'client',
                    fn (Builder $customerQuery) => $customerQuery->where('phone', 'like', '%'.$filters->phone.'%')
                )
            );
    }
}
