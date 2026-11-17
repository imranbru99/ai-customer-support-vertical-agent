@extends('layouts.marketing')
@section('title', 'Demo — SupportPilot AI')
@section('content')
<h1 class="section-title">Demo access</h1>
<div class="card" style="max-width:520px">
    <p>Filament admin: <a href="/admin">/admin</a></p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>Also seeded: superadmin@supportpilot.test, admin@supportpilot.test, manager@supportpilot.test, agent@supportpilot.test (password for all).</p>
    <p><a class="btn" href="/admin">Open admin</a> <a class="btn ghost" href="{{ route('portal') }}">Customer portal</a></p>
</div>
@endsection
