<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Schedule</title>
    @vite('resources/css/app.css')
</head>
<body style="padding:2rem;font-family:var(--font-sans);background:#f8fafc;">
    <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:1rem;">Weekly Schedule</h1>
    <a href="{{ route('dashboard') }}" style="color:#b91c1c;text-decoration:none;">← Back to dashboard</a>
    <div style="margin-top:1rem;display:grid;gap:1rem;">
        @foreach($weekSchedule as $day => $items)
            <section style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1rem;">
                <h2 style="font-size:1rem;font-weight:700;margin-bottom:.75rem;">{{ $day }}</h2>
                @if(count($items))
                    <table style="width:100%;border-collapse:collapse;">
                        <thead><tr><th style="text-align:left;padding:.5rem;border-bottom:1px solid #e5e7eb;">Time</th><th style="text-align:left;padding:.5rem;border-bottom:1px solid #e5e7eb;">Course</th></tr></thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td style="padding:.5rem;border-bottom:1px solid #f3f4f6;">{{ $item['time_slot'] ?: '—' }}</td>
                                <td style="padding:.5rem;border-bottom:1px solid #f3f4f6;"><a style="color:#b91c1c;" href="{{ route('courses.show', $item['course_id']) }}">{{ $item['course_code'] ? $item['course_code'] . ' - ' : '' }}{{ $item['course_name'] }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color:#6b7280;">No schedules.</p>
                @endif
            </section>
        @endforeach
    </div>
</body>
</html>
