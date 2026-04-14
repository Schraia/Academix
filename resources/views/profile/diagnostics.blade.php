<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In-depth Diagnostics</title>
    @vite('resources/css/app.css')
</head>
<body style="font-family:var(--font-sans);padding:2rem;background:#f8fafc;">
    <a href="{{ route('profile.show') }}" style="color:#b91c1c;text-decoration:none;">← Back to profile</a>
    <h1 style="margin-top:.75rem;font-size:1.5rem;font-weight:700;">In-depth Diagnostics</h1>

    <section style="margin-top:1rem;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.75rem;">Module completions by weekday</h2>
        <div style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:.5rem;align-items:end;height:200px;">
            @php $maxCompleted = max(1, max($completedByWeekday)); @endphp
            @foreach($completedByWeekday as $day => $count)
                @php $height = $count > 0 ? max(12, intval(($count / $maxCompleted) * 180)) : 4; @endphp
                <div style="display:flex;flex-direction:column;align-items:center;justify-content:flex-end;">
                    <div style="font-size:.75rem;color:#6b7280;">{{ $count }}</div>
                    <div style="width:26px;height:{{ $height }}px;background:#dc2626;border-radius:6px 6px 0 0;"></div>
                    <div style="font-size:.75rem;margin-top:.3rem;">{{ $day }}</div>
                </div>
            @endforeach
        </div>
    </section>

    <section style="margin-top:1rem;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:.75rem;">Estimated site activity by weekday</h2>
        <div style="display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:.5rem;">
            @foreach($sessionsByWeekday as $day => $count)
                <div style="padding:.5rem;border:1px solid #f1f5f9;border-radius:8px;text-align:center;">
                    <div style="font-size:.75rem;color:#6b7280;">{{ $day }}</div>
                    <div style="font-size:1.1rem;font-weight:700;color:#0f172a;">{{ $count }}</div>
                    <div style="font-size:.7rem;color:#94a3b8;">session hits</div>
                </div>
            @endforeach
        </div>
    </section>
</body>
</html>
