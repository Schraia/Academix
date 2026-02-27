@extends('course-show-layout')
@section('title', $course->title . ' - Grade Weights')
@section('page_heading', 'Grade Weights')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <p style="margin-bottom: 1rem;">Define what percentage each category contributes to the final grade (must total 100%).</p>
    <div class="courses-card" style="padding: 1.5rem; max-width: 480px;">
        <form action="{{ route('courses.grade-weights.update', $course) }}" method="POST">
            @csrf
            @error('percentages')<p style="color: #dc2626; margin-bottom: 0.75rem;">{{ $message }}</p>@enderror
            @foreach(['exam' => 'Exam', 'quiz' => 'Quiz', 'activity' => 'Activity', 'attendance' => 'Attendance'] as $cat => $label)
                <div style="margin-bottom: 1rem;">
                    <label for="weight-{{ $cat }}" style="display: block; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">{{ $label }} (%)</label>
                    <input type="number" id="weight-{{ $cat }}" name="{{ $cat }}" value="{{ old($cat, $weights->get($cat)?->percentage ?? 0) }}" min="0" max="100" step="0.01" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>
            @endforeach
            <button type="submit" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Save weights</button>
        </form>
    </div>
    <p style="margin-top: 1rem;"><a href="{{ route('courses.grades', $course) }}" class="back-link">← Back to Grades</a></p>
@endsection
