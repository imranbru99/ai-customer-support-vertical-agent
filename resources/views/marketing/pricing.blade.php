@extends('layouts.marketing')
@section('title', 'Pricing — SupportPilot AI')
@section('content')
<h1 class="section-title">Pricing & ROI</h1>
<p>With {{ $enabledCount }} / {{ $moduleCount }} modules enabled, demo capacity is about <strong>{{ $weeklyHours }} hours/week</strong> (~${{ number_format($weeklyValue) }} at $55/hr blended support cost).</p>
<section class="grid">
    <div class="card"><h3>Starter</h3><p>Core triage, knowledge, macros for a single vertical.</p><p><strong>$499 / mo</strong></p></div>
    <div class="card"><h3>Growth</h3><p>Escalation routing, CSAT recovery, multi-industry packs.</p><p><strong>$1,299 / mo</strong></p></div>
    <div class="card"><h3>Enterprise</h3><p>AI answer assist, PDF packs, attendance & catalog bridges.</p><p><strong>Custom</strong></p></div>
</section>
@endsection
