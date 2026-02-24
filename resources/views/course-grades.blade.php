@extends('course-show-layout')
@section('title', $course->title . ' - Grades')
@section('page_heading', 'Grades')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="overflow: hidden;">
        <table class="courses-table">
            <thead>
                <tr>
                    @if($isInstructor)<th>Student</th>@endif
                    <th>Item</th>
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
                    <tr><td colspan="{{ $isInstructor ? 5 : 3 }}" style="color: #6b7280;">No grades recorded yet for this course.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
