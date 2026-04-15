<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Tickets\BuildTicketStatisticsAction;
use App\Data\TicketFilterData;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TicketFilterRequest;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TicketController extends Controller
{
    public function index(TicketFilterRequest $request, BuildTicketStatisticsAction $buildTicketStatistics): View
    {
        $filters = TicketFilterData::fromArray($request->validated());

        $tickets = Ticket::query()
            ->with(['client', 'media'])
            ->latest()
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        return view('admin.tickets.index', [
            'filters' => $filters,
            'statistics' => $buildTicketStatistics->execute(),
            'statusOptions' => TicketStatus::options(),
            'tickets' => $tickets,
        ]);
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load(['client', 'media']);

        return view('admin.tickets.show', [
            'statusOptions' => TicketStatus::options(),
            'ticket' => $ticket,
        ]);
    }

    public function update(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $status = TicketStatus::from($request->validated('status'));

        $ticket->update([
            'status' => $status,
            'manager_responded_at' => $status === TicketStatus::Processed
                ? ($ticket->manager_responded_at ?? now())
                : null,
        ]);

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('status', 'Статус заявки оновлено.');
    }

    public function download(Ticket $ticket, int $mediaId): BinaryFileResponse
    {
        $media = $ticket->media()->findOrFail($mediaId);

        return response()->download($media->getPath(), $media->file_name);
    }
}
