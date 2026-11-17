@extends('layouts.marketing')
@section('title', 'Customer Portal — SupportPilot AI')
@section('content')
<h1 class="section-title">Customer portal</h1>
<p>Recent tickets visible to customer-role users.</p>
<table>
    <thead><tr><th>Ticket</th><th>Subject</th><th>Status</th></tr></thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->number }}</td>
            <td>{{ $ticket->subject }}</td>
            <td>{{ $ticket->status?->value }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
