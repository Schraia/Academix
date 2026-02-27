@extends('course-show-layout')
@section('title', $course->title . ' - Grade Section')
@section('page_heading', 'Grade Section')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <a href="{{ route('courses.grades', $course) }}{{ $section ? '?section=' . urlencode($section) : '' }}" class="back-link" style="margin-bottom: 1rem;">← Back to Grades</a>

    <div class="courses-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
        <form action="{{ route('courses.grade-section.store', $course) }}" method="POST" id="grade-section-form">
            @csrf
            <input type="hidden" name="section_code" value="{{ $section ?? '' }}">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end; margin-bottom: 1rem; flex-wrap: wrap;">
                <div>
                    <label for="name" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Item name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
                <div>
                    <label for="category" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Category *</label>
                    <select id="category" name="category" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="exam" {{ old('category') === 'exam' ? 'selected' : '' }}>Exam</option>
                        <option value="quiz" {{ old('category') === 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="activity" {{ old('category') === 'activity' ? 'selected' : '' }}>Activity</option>
                    </select>
                </div>
                <div>
                    <label for="max_score" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">Max score</label>
                    <input type="number" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" step="0.01" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            </div>
            <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.75rem;">Enter score for each student (leave blank to skip). Use Tab or arrow keys to move between fields.</p>
            <table class="courses-table" style="margin-bottom: 1rem;">
                <thead>
                    <tr><th>Student</th><th>Score</th></tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $e)
                        <tr>
                            <td>{{ $e->user->name ?? '—' }}</td>
                            <td style="width: 120px;">
                                <input type="number" name="scores[{{ $e->user_id }}]" value="{{ old('scores.'.$e->user_id) }}" step="0.01" placeholder="—" class="score-input" style="width: 100%; padding: 0.35rem 0.5rem; border: 1px solid #d1d5db; border-radius: 6px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Save grades</button>
        </form>
    </div>
    <script>
    document.querySelectorAll('.score-input').forEach(function(input, i, arr) {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown' && i < arr.length - 1) { e.preventDefault(); arr[i + 1].focus(); }
            if (e.key === 'ArrowUp' && i > 0) { e.preventDefault(); arr[i - 1].focus(); }
        });
    });
    </script>
@endsection
