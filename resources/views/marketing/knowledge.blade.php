@extends('layouts.marketing')
@section('title', 'Knowledge — SupportPilot AI')
@section('content')
<h1 class="section-title">Knowledge answers</h1>
<p>Industry-tuned articles power AI deflection and agent assist.</p>
<section class="grid">
@foreach($articles as $article)
    <div class="card">
        <span class="badge">{{ $article->category }}</span>
        <h3>{{ $article->title }}</h3>
        <p>{{ $article->excerpt }}</p>
        <p><small>{{ optional($article->industry)->name }} · {{ $article->helpful_votes }} helpful</small></p>
    </div>
@endforeach
</section>
@endsection
