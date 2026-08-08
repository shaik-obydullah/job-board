<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f9fafb; color: #111827; display: grid; place-items: center; height: 100vh; margin: 0; }
        .card { text-align: center; padding: 2.5rem; }
        .ok { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.125rem; font-weight: 600; color: #059669; }
        .dot { width: .625rem; height: .625rem; border-radius: 9999px; background: #059669; }
    </style>
</head>
<body>
    <div class="card">
        <span class="ok"><span class="dot"></span> All systems operational</span>
        <p style="color:#6b7280; margin-top:.5rem;">{{ config('app.name') }} is running.</p>
    </div>
</body>
</html>
