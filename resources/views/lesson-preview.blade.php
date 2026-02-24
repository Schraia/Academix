@extends('course-show-layout')
@section('title', $lesson->title . ' - Preview')
@section('page_heading', $lesson->title)
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1rem 1.5rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
            <a href="{{ route('courses.lessons', $course) }}" class="back-link">← Back to lessons</a>
            <a href="{{ $fileUrl }}" download class="btn-download" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">Download file</a>
        </div>
        @if($canPreview)
            @if($extension === 'pdf')
                <iframe src="{{ $fileUrl }}#toolbar=1" style="width: 100%; height: 75vh; border: 1px solid #e5e7eb; border-radius: 8px;" title="PDF preview"></iframe>
            @else
                <img src="{{ $fileUrl }}" alt="{{ $lesson->title }}" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #e5e7eb;">
            @endif
        @else
            <p style="color: #6b7280; margin-bottom: 0.5rem;">Preview is not available for this file type. Use the button above to download and open it on your device.</p>
            <a href="{{ $fileUrl }}" download style="color: #dc2626; font-weight: 600;">Download file</a>
        @endif
    </div>
@endsection
