@extends('course-show-layout')
@section('title', $course->title . ' - Roll Call')
@section('page_heading', 'Roll Call')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <a href="{{ route('courses.grades', $course) }}" class="back-link" style="margin-bottom: 1rem;">← Back to Grades</a>

    <div class="courses-card" style="padding: 1.5rem; margin-bottom: 1rem;">
        <form method="get" action="{{ route('courses.rollcall', $course) }}" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
            <div>
                <label for="date" style="font-weight: 600; margin-right: 0.5rem;">Date</label>
                <input type="date" id="date" name="date" value="{{ $date ?? now()->format('Y-m-d') }}" style="padding: 0.35rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;">
            </div>
            <div>
                <label for="section" style="font-weight: 600; margin-right: 0.5rem;">Section</label>
                <select name="section" id="section" style="padding: 0.35rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;">
                    <option value="">— All —</option>
                    @foreach($sections ?? [] as $sec)
                        <option value="{{ $sec }}" {{ (isset($section) && $section === $sec) ? 'selected' : '' }}>{{ $sec }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" style="padding: 0.35rem 0.75rem; background: #374151; color: white; border: none; border-radius: 6px; cursor: pointer;">Load</button>
        </form>
        <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.75rem;">Click to cycle: <strong>Present</strong> (100) → <strong>Late</strong> (75) → <strong>Absent</strong> (50) → <strong>None</strong> (no grade)</p>
    </div>

    <form action="{{ route('courses.rollcall.store', $course) }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date ?? now()->format('Y-m-d') }}">
        <input type="hidden" name="section_code" value="{{ $section ?? '' }}">
        <div class="courses-card" style="padding: 1.5rem;">
            @if(session('success'))<p style="color: #059669; margin-bottom: 0.75rem;">{{ session('success') }}</p>@endif
            <table class="courses-table">
                <thead>
                    <tr><th>Student</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $e)
                        @php $rec = $attendance->get($e->user_id); $current = $rec ? $rec->status : 'present'; @endphp
                        <tr>
                            <td>{{ $e->user->name ?? '—' }}</td>
                            <td>
                                <input type="hidden" name="status[{{ $e->user_id }}]" id="status-{{ $e->user_id }}" value="{{ $current }}">
                                <button type="button" class="rollcall-btn" data-user-id="{{ $e->user_id }}" style="padding: 0.35rem 0.75rem; border-radius: 6px; font-weight: 600; cursor: pointer; border: 1px solid #d1d5db; min-width: 90px;"
                                    data-cycle="present late absent none">
                                    <span class="label">{{ ucfirst($current) }}</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="color: #6b7280;">No students in this section.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($enrollments->isNotEmpty())
            <p style="margin-top: 1rem;"><button type="submit" style="padding: 0.5rem 1rem; background: #059669; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Save roll call</button></p>
            @endif
        </div>
    </form>
    <script>
    document.querySelectorAll('.rollcall-btn').forEach(function(btn) {
        var cycles = ['present', 'late', 'absent', 'none'];
        var labels = ['Present', 'Late', 'Absent', 'None'];
        var colors = { present: '#22c55e', late: '#eab308', absent: '#dc2626', none: '#9ca3af' };
        var userId = btn.getAttribute('data-user-id');
        var hidden = document.getElementById('status-' + userId);
        var idx = cycles.indexOf(hidden.value);
        if (idx < 0) idx = 0;
        btn.querySelector('.label').textContent = labels[idx];
        btn.style.backgroundColor = colors[cycles[idx]] || '#e5e7eb';
        btn.addEventListener('click', function() {
            idx = (idx + 1) % 4;
            hidden.value = cycles[idx];
            btn.querySelector('.label').textContent = labels[idx];
            btn.style.backgroundColor = colors[cycles[idx]];
        });
    });
    </script>
@endsection
