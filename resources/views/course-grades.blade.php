@extends('course-show-layout')
@section('title', $course->title . ' - Grades')
@section('page_heading', 'Grades')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    @if($isInstructor)
    <p style="margin-bottom: 1rem;">
        <a href="{{ route('courses.upload.grades', $course) }}?return_to=grades{{ isset($selectedSection) && $selectedSection !== '' ? '&section=' . urlencode($selectedSection) : '' }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9375rem;">Upload Grade</a>
        <a href="{{ route('courses.grade-weights', $course) }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #374151; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9375rem; margin-left: 0.5rem;">Define grade weights</a>
        <a href="{{ route('courses.rollcall', $course) }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #059669; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9375rem; margin-left: 0.5rem;">Roll call</a>
    </p>
    <div style="margin-bottom: 1rem;">
        <form method="get" action="{{ route('courses.grades', $course) }}" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <label for="section" style="font-size: 0.9375rem;">Section:</label>
            <select name="section" id="section" onchange="this.form.submit()" style="padding: 0.35rem 0.75rem; border: 1px solid #d1d5db; border-radius: 6px;">
                <option value="">— All —</option>
                @foreach($sections ?? [] as $sec)
                    <option value="{{ $sec }}" {{ (isset($selectedSection) && $selectedSection === $sec) ? 'selected' : '' }}>{{ $sec }}</option>
                @endforeach
            </select>
        </form>
    </div>
    @if(isset($sectionStudents) && $sectionStudents->isNotEmpty() && (isset($selectedSection) && $selectedSection !== ''))
    <div class="courses-card" style="padding: 1rem 1.5rem; margin-bottom: 1rem;">
        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem;">Students in section</h3>
        <p style="margin-bottom: 0.5rem;"><a href="{{ route('courses.grade-section', $course) }}?section={{ urlencode($selectedSection ?? '') }}" style="color: #dc2626; font-weight: 600; font-size: 0.875rem;">Grade section</a> — Enter one grade item for all students</p>
        <table class="courses-table" style="font-size: 0.9375rem;">
            <thead><tr><th>Student</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($sectionStudents as $e)
                    <tr>
                        <td>{{ $e->user->name ?? '—' }} ({{ $e->user->email ?? '' }})</td>
                        <td><a href="{{ route('courses.upload.grades', $course) }}?return_to=grades&section={{ urlencode($selectedSection ?? '') }}&user_id={{ $e->user_id }}" style="color: #2563eb;">Grade</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif
    @if(!$isInstructor && isset($gradeSummary) && $gradeSummary['weights_defined'])
    <div class="courses-card" style="padding: 1rem 1.5rem; margin-bottom: 1rem; background: #f0f9ff; border: 1px solid #bae6fd;">
        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Your grade summary</h3>
        <ul style="list-style: none; padding: 0; margin: 0 0 0.5rem 0;">
            @foreach(['exam' => 'Exam', 'quiz' => 'Quiz', 'activity' => 'Activity', 'attendance' => 'Attendance'] as $cat => $label)
                @if(isset($gradeSummary['by_category'][$cat]) && $gradeSummary['by_category'][$cat] !== null)
                    <li style="font-size: 0.9375rem;">{{ $label }}: {{ $gradeSummary['by_category'][$cat] }}%</li>
                @endif
            @endforeach
        </ul>
        @if($gradeSummary['weighted_grade'] !== null)
            <p style="font-weight: 700; font-size: 1rem;">Weighted total: {{ $gradeSummary['weighted_grade'] }}%</p>
        @endif
    </div>
    @endif
    <div class="courses-card" style="overflow: hidden;">
        <table class="courses-table">
            <thead>
                <tr>
                    @if($isInstructor)<th>Student</th>@endif
                    <th>Item</th>
                    <th>Category</th>
                    <th>Score</th>
                    <th>Graded</th>
                    @if($isInstructor)<th>Actions</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $g)
                    <tr>
                        @if($isInstructor)<td>{{ $g->user->name ?? '—' }}</td>@endif
                        <td>{{ $g->name }}</td>
                        <td>{{ $g->category ? ucfirst($g->category) : '—' }}</td>
                        <td>{{ $g->score !== null ? $g->score . ' / ' . $g->max_score : '—' }}</td>
                        <td>{{ $g->graded_at ? $g->graded_at->format('M j, Y') : '—' }}</td>
                        @if($isInstructor)
                            <td style="white-space: nowrap;">
                                <a href="{{ route('courses.grades.edit', [$course, $g]) }}" style="color: #2563eb; font-size: 0.875rem;">Edit</a>
                                <form action="{{ route('courses.grades.toggle', [$course, $g]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: #2563eb; font-size: 0.875rem; cursor: pointer;">{{ $g->is_visible ? 'Hide' : 'Show' }}</button>
                                </form>
                                <form action="{{ route('courses.grades.destroy', [$course, $g]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this grade?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 0.875rem; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ $isInstructor ? 6 : 4 }}" style="color: #6b7280;">No grades recorded yet for this course.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
