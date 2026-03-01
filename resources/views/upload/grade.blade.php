@extends('course-show-layout')
@section('title', $course->title . ' - Upload Grade')
@section('page_heading', 'Upload Grade')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.upload.grades.store', $course) }}" method="POST">
            @csrf
            @if(request('return_to'))<input type="hidden" name="return_to" value="{{ request('return_to') }}">@endif
            <div class="form-group">
                <label for="section_code">Section</label>
                <select id="section_code" name="section_code">
                    <option value="">— Select section —</option>
                    @foreach($sections ?? [] as $sec)
                        <option value="{{ $sec }}" {{ old('section_code', $prefillSection ?? '') === $sec ? 'selected' : '' }}>{{ $sec }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="user_id">Student *</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Select student</option>
                    @foreach($enrolledUsers as $e)
                        @php $secVal = $e->section_code ?? $e->section_name ?? ''; @endphp
                        <option value="{{ $e->user_id }}" data-section="{{ $secVal }}" {{ old('user_id', $prefillUserId ?? '') == $e->user_id ? 'selected' : '' }}>{{ $e->user->name }} ({{ $e->user->email }})@if($secVal) — {{ $secVal }}@endif</option>
                    @endforeach
                </select>
                @error('user_id') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="name">Item name (e.g. Quiz 1, Midterm) *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <option value="">Select category</option>
                    <option value="exam" {{ old('category') === 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="quiz" {{ old('category') === 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="activity" {{ old('category') === 'activity' ? 'selected' : '' }}>Activity</option>
                </select>
                @error('category') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="score">Score</label>
                <input type="number" id="score" name="score" step="0.01" value="{{ old('score') }}" placeholder="e.g. 85">
            </div>
            <div class="form-group">
                <label for="max_score">Max score</label>
                <input type="number" id="max_score" name="max_score" step="0.01" value="{{ old('max_score', 100) }}">
            </div>
            <button type="submit" class="btn-submit">Record grade</button>
        </form>
    </div>
@endsection
