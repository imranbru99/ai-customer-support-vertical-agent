@extends('layouts.marketing')
@section('title', 'Features — SupportPilot AI')
@section('content')
<h1 class="section-title">Feature map by category</h1>
<p>Every capability is modular — turn categories on or off without rewriting your support stack.</p>
@foreach($modules as $category => $items)
    <h2 class="section-title" style="font-size:1.4rem">{{ ucfirst($category) }}</h2>
    <section class="grid">
    @foreach($items as $module)
        <div class="card">
            <span class="badge">{{ ($module['enabled'] ?? false) ? 'On' : 'Off' }}</span>
            <h3>{{ $module['name'] }}</h3>
            <p>{{ $module['description'] }}</p>
        </div>
    @endforeach
    </section>
@endforeach
@endsection
