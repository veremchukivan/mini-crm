@extends('layouts.admin')

@section('title', 'Заявка #'.$ticket->id.' | Mini CRM')
@section('page_title', 'Заявка #'.$ticket->id)

@section('content')
    <div style="margin-bottom: 18px;">
        <a class="button-secondary" href="{{ route('admin.tickets.index') }}">Повернутися до списку</a>
    </div>

    <section class="detail-grid">
        <article class="panel detail-card">
            <h2>{{ $ticket->subject }}</h2>

            <div class="detail-list">
                <div>
                    <strong>Статус</strong>
                    <span class="status-badge" data-status="{{ $ticket->status->value }}">{{ $ticket->status->label() }}</span>
                </div>

                <div>
                    <strong>Текст заявки</strong>
                    <div class="meta-text">{{ $ticket->message }}</div>
                </div>

                <div>
                    <strong>Клієнт</strong>
                    <div class="meta-text">
                        {{ $ticket->client->name }}<br>
                        {{ $ticket->client->email }}<br>
                        {{ $ticket->client->phone }}
                    </div>
                </div>

                <div>
                    <strong>Створено</strong>
                    <div class="meta-text">{{ $ticket->created_at->format('d.m.Y H:i:s') }}</div>
                </div>

                <div>
                    <strong>Дата відповіді менеджера</strong>
                    <div class="meta-text">
                        {{ $ticket->manager_responded_at?->format('d.m.Y H:i:s') ?? 'Ще не відповіли' }}
                    </div>
                </div>
            </div>
        </article>

        <aside class="detail-grid" style="grid-template-columns: 1fr; gap: 18px;">
            <section class="panel detail-card">
                <h2>Оновити статус</h2>

                <form method="post" action="{{ route('admin.tickets.update', $ticket) }}">
                    @csrf
                    @method('patch')

                    <div class="field">
                        <label for="status">Новий статус</label>
                        <select id="status" name="status">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($ticket->status->value === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="meta-text" style="color:#a03c1e;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="margin-top: 18px;">
                        <button class="button" type="submit">Зберегти</button>
                    </div>
                </form>
            </section>

            <section class="panel detail-card">
                <h2>Прикріплені файли</h2>

                @if ($ticket->getMedia('attachments')->isEmpty())
                    <div class="meta-text">Файли не прикріплені.</div>
                @else
                    <div class="attachment-list">
                        @foreach ($ticket->getMedia('attachments') as $media)
                            <div class="attachment-item">
                                <div>
                                    <strong>{{ $media->file_name }}</strong>
                                    <div class="meta-text">{{ $media->mime_type }} · {{ number_format($media->size / 1024, 1) }} KB</div>
                                </div>

                                <a class="button-secondary" href="{{ route('admin.tickets.download', [$ticket, $media->id]) }}">
                                    Завантажити
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </aside>
    </section>
@endsection
