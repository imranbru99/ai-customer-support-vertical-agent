@extends('layouts.marketing')
@section('title', 'Modules — SupportPilot AI')
@section('content')
<h1 class="section-title">Toggleable modules</h1>
<p>Estimated capacity from enabled modules: <strong>{{ $weeklyHours }} hours / week</strong>.</p>
<table>
    <thead><tr><th>Module</th><th>Category</th><th>Core</th><th>Status</th><th>Hrs/wk</th></tr></thead>
    <tbody>
    @foreach($modules as $module)
        <tr>
            <td><strong>{{ $module['name'] }}</strong><br><small>{{ $module['description'] }}</small></td>
            <td>{{ $module['category'] }}</td>
            <td>{{ ($module['core'] ?? false) ? 'Yes' : 'No' }}</td>
            <td>{{ ($module['enabled'] ?? false) ? 'Enabled' : 'Disabled' }}</td>
            <td>{{ $module['hours_saved_weekly'] ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
