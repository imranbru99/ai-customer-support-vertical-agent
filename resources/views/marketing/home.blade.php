@extends('layouts.marketing')
@section('title', 'SupportPilot AI — Vertical Customer Support Agent')
@section('content')
<section class="hero">
    <div class="badge">Vertical AI support</div>
    <h1>AI Customer Support Agent for industry-tuned triage</h1>
    <p>Ticket triage, knowledge answers, escalation routing, and CSAT automation — enable only the modules your support org needs.</p>
    <div class="cta">
        <a class="btn" href="{{ route('demo') }}">Launch demo</a>
        <a class="btn ghost" href="{{ route('modules') }}">Browse modules</a>
    </div>
</section>
<section style="padding:0 0 1.5rem;">
    <img src="{{ asset('docs/screenshots/dashboard.png') }}" alt="SupportPilot AI dashboard with blue #2563EB accent showing ticket triage, escalation routing, and CSAT" style="width:100%;border-radius:.9rem;border:1px solid #bfdbfe;box-shadow:0 20px 40px rgba(37,99,235,.12);">
</section>
<section class="grid">
    <div class="card"><span class="badge">Queue</span><h3>{{ $openTickets }} open tickets</h3><p>Live triage queue across verticals.</p></div>
    <div class="card"><span class="badge">Knowledge</span><h3>{{ $articles }} articles</h3><p>Industry knowledge packs ready for deflection.</p></div>
    <div class="card"><span class="badge">CSAT</span><h3>{{ $avgCsat ?: '—' }} avg score</h3><p>Post-resolution satisfaction tracking.</p></div>
    <div class="card"><span class="badge">ROI</span><h3>{{ $weeklyHours }} hrs / week</h3><p>{{ $enabledCount }} modules enabled in this deploy.</p></div>
</section>
<h2 class="section-title">Industry verticals</h2>
<section class="grid">
@foreach($verticals as $v)
    <div class="card">
        <span class="badge">{{ $v->sector }}</span>
        <h3>{{ $v->name }}</h3>
        <p>SLA {{ $v->sla_minutes }} min · deflection target {{ number_format($v->deflection_target * 100, 0) }}% · tone {{ $v->tone }}.</p>
    </div>
@endforeach
</section>
@endsection
