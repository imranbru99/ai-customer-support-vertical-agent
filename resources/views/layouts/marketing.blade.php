<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AI Customer Support Vertical Agent')</title>
    <meta name="description" content="@yield('meta_description', 'Vertical AI customer support agent for ticket triage, knowledge answers, escalation routing, and CSAT-improving automation tailored by industry.')">
    <meta name="author" content="Imran Ahmed <me@imrandev.bd>">
    <meta name="theme-color" content="#2563EB">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --accent:#2563EB; --ink:#0f172a; --muted:#475569; --bg:#eff6ff; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:'Source Sans 3',sans-serif; color:var(--ink); background:
            radial-gradient(1000px 420px at 90% -10%, color-mix(in srgb, var(--accent) 28%, white), transparent),
            linear-gradient(160deg, #dbeafe 0%, #f8fafc 42%, #fff 100%); }
        a { color: var(--accent); text-decoration: none; }
        .wrap { width: min(1120px, 92%); margin: 0 auto; }
        header { display:flex; justify-content:space-between; align-items:center; padding:1.25rem 0; }
        .brand { font-family:'Sora',sans-serif; font-size:1.55rem; font-weight:700; color:var(--accent); }
        nav { display:flex; gap:1rem; flex-wrap:wrap; align-items:center; }
        nav a { color:var(--muted); font-weight:600; }
        .btn { display:inline-block; background:var(--accent); color:#fff !important; padding:.75rem 1.15rem; border-radius:.5rem; font-weight:700; }
        .btn.ghost { background:transparent; color:var(--accent) !important; border:1px solid color-mix(in srgb, var(--accent) 45%, white); }
        .hero { padding: 3.4rem 0 2.2rem; display:grid; gap:1rem; max-width: 46rem; }
        .hero h1 { font-family:'Sora',sans-serif; font-size: clamp(2.2rem, 5vw, 3.4rem); line-height:1.08; margin:0; color:var(--accent); }
        .hero p { font-size:1.125rem; color:var(--muted); margin:0; }
        .cta { display:flex; gap:.75rem; flex-wrap:wrap; margin-top:.75rem; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); gap:1rem; padding: 1.5rem 0 3rem; }
        .card { background: rgba(255,255,255,.88); border:1px solid #bfdbfe; border-radius:.75rem; padding:1.25rem; }
        .card h3 { margin:.2rem 0 .5rem; font-size:1.1rem; }
        .card p { margin:0; color:var(--muted); }
        .badge { display:inline-block; font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:var(--accent); }
        footer { border-top:1px solid #bfdbfe; padding:2rem 0; color:var(--muted); font-size:.95rem; }
        .section-title { font-family:'Sora',sans-serif; font-size:2rem; color:var(--accent); margin:2rem 0 .5rem; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { text-align:left; padding:.7rem .8rem; border-bottom:1px solid #e2e8f0; font-size:.95rem; }
        th { background:#eff6ff; color:#1e3a8a; }
        @media (max-width:720px){ header{flex-direction:column; align-items:flex-start; gap:1rem;} }
    </style>
</head>
<body>
<div class="wrap">
    <header>
        <a class="brand" href="{{ route('home') }}">SupportPilot AI</a>
        <nav>
            <a href="{{ route('features') }}">Features</a>
            <a href="{{ route('modules') }}">Modules</a>
            <a href="{{ route('knowledge') }}">Knowledge</a>
            <a href="{{ route('triage') }}">Triage</a>
            <a href="{{ route('pricing') }}">Pricing</a>
            <a href="{{ route('demo') }}">Demo</a>
            <a class="btn" href="/admin">Admin Login</a>
        </nav>
    </header>
    @yield('content')
    <footer>
        <p><strong>AI Customer Support Vertical Agent</strong> — built by <a href="https://imrandev.bd">Imran Ahmed</a> · <a href="mailto:me@imrandev.bd">me@imrandev.bd</a></p>
        <p>Toggleable modules for ticket triage, knowledge answers, escalation routing, CSAT automation, and industry macros.</p>
    </footer>
</div>
</body>
</html>
