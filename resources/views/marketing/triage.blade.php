@extends('layouts.marketing')
@section('title', 'Triage — SupportPilot AI')
@section('content')
<h1 class="section-title">Ticket triage preview</h1>
<table>
    <thead><tr><th>ID</th><th>Subject</th><th>Industry</th><th>Priority</th><th>AI</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->number }}</td>
            <td>{{ $ticket->subject }}</td>
            <td>{{ optional($ticket->industry)->name }}</td>
            <td>{{ $ticket->priority?->value }}</td>
            <td>{{ $ticket->ai_score }}</td>
            <td>{{ $ticket->status?->value }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<h2 class="section-title">Macros</h2>
<section class="grid">
@foreach($macros as $macro)
    <div class="card">
        <span class="badge">{{ $macro->shortcut }}</span>
        <h3>{{ $macro->name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($macro->body, 140) }}</p>
    </div>
@endforeach
</section>
@endsection
