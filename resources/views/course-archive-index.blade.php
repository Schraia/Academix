@extends('course-show-layout')
@section('title', $course->title . ' - Course Archive')
@section('page_heading', 'Course archive')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">Past lesson snapshots for {{ $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($archives as $a)
            <div style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
                <a href="{{ route('courses.archive.show', [$course, $a]) }}" style="color: #dc2626; font-weight: 700; text-decoration: none;">{{ $a->label }}</a>
                <p style="font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">Archived {{ $a->created_at->format('M j, Y') }}</p>
            </div>
        @empty
            <p style="color: #6b7280;">No archived timeframes for this course yet.</p>
        @endforelse
    </div>
@endsection
