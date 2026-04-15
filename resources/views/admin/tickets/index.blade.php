@extends('layouts.admin')

@section('title', 'Заявки | Mini CRM')
@section('page_title', 'Заявки')

@section('content')
    <section class="stats-grid">
        <article class="panel stat-card">
            <span>За добу</span>
            <strong>{{ $statistics['day'] }}</strong>
        </article>
        <article class="panel stat-card">
            <span>За тиждень</span>
            <strong>{{ $statistics['week'] }}</strong>
        </article>
        <article class="panel stat-card">
            <span>За місяць</span>
            <strong>{{ $statistics['month'] }}</strong>
        </article>
    </section>

    <section class="panel filter-panel">
        <form method="get">
            <div class="filter-grid">
                <div class="field">
                    <label for="status">Статус</label>
                    <select id="status" name="status">
                        <option value="">Усі</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($filters->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="date_from">Від дати</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters->dateFrom }}">
                </div>

                <div class="field">
                    <label for="date_to">До дати</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters->dateTo }}">
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="text" value="{{ $filters->email }}" placeholder="name@example.com">
                </div>

                <div class="field">
                    <label for="phone">Телефон</label>
                    <input id="phone" name="phone" type="text" value="{{ $filters->phone }}" placeholder="+380...">
                </div>
            </div>

            <div style="display:flex; gap:12px; margin-top:18px;">
                <button class="button" type="submit">Фільтрувати</button>
                <a class="button-secondary" href="{{ route('admin.tickets.index') }}">Скинути</a>
            </div>
        </form>
    </section>

    <section class="panel content-panel">
        @if ($tickets->isEmpty())
            <div class="empty-state">За обраними фільтрами заявок не знайдено.</div>
        @else
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Клієнт</th>
                        <th>Тема</th>
                        <th>Статус</th>
                        <th>Дата</th>
                        <th>Файли</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>
                                <strong>{{ $ticket->client->name }}</strong><br>
                                <span class="meta-text">{{ $ticket->client->email }}<br>{{ $ticket->client->phone }}</span>
                            </td>
                            <td>
                                <strong>{{ $ticket->subject }}</strong><br>
                                <span class="meta-text">{{ \Illuminate\Support\Str::limit($ticket->message, 90) }}</span>
                            </td>
                            <td>
                                <span class="status-badge" data-status="{{ $ticket->status->value }}">
                                    {{ $ticket->status->label() }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $ticket->getMedia('attachments')->count() }}</td>
                            <td>
                                <a class="button-secondary" href="{{ route('admin.tickets.show', $ticket) }}">Деталі</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($tickets->hasPages())
                <div class="pagination" style="display:flex; justify-content:space-between; gap:12px; align-items:center;">
                    <span class="meta-text">
                        Сторінка {{ $tickets->currentPage() }} з {{ $tickets->lastPage() }}
                    </span>

                    <div style="display:flex; gap:12px;">
                        @if ($tickets->onFirstPage())
                            <span class="button-secondary" style="opacity:.55; pointer-events:none;">Попередня</span>
                        @else
                            <a class="button-secondary" href="{{ $tickets->previousPageUrl() }}">Попередня</a>
                        @endif

                        @if ($tickets->hasMorePages())
                            <a class="button-secondary" href="{{ $tickets->nextPageUrl() }}">Наступна</a>
                        @else
                            <span class="button-secondary" style="opacity:.55; pointer-events:none;">Наступна</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </section>
@endsection
