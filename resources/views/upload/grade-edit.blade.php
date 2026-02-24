@extends('course-show-layout')
@section('title', $course->title . ' - Edit Grade')
@section('page_heading', 'Edit Grade')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group .readonly { background: #f3f4f6; color: #6b7280; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.grades.update', [$course, $grade]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Student</label>
                <input type="text" class="readonly" value="{{ $grade->user->name ?? '—' }} ({{ $grade->user->email ?? '' }})" readonly>
            </div>
            <div class="form-group">
                <label for="name">Item name (e.g. Quiz 1, Midterm) *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $grade->name) }}" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" id="score" name="score" step="0.01" value="{{ old('score', $grade->score) }}" placeholder="e.g. 85">
            </div>
            <div class="form-group">
                <label for="max_score">Max score</label>
                <input type="number" id="max_score" name="max_score" step="0.01" value="{{ old('max_score', $grade->max_score ?? 100) }}">
            </div>
            <button type="submit" class="btn-submit">Update grade</button>
        </form>
    </div>
@endsection
