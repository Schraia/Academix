@extends('course-show-layout')
@section('title', $course->title . ' - Archive')
@section('page_heading', $archive->label)
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;"><a href="{{ route('courses.archive.index', $course) }}" style="color:#dc2626;">← All archives</a> · {{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        <h3 style="font-size: 1rem; margin-bottom: 1rem;">Lessons in this archive</h3>
        @forelse($archive->archivedLessons as $al)
            <div style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                <strong>{{ $al->title }}</strong>
                @if($al->description)
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">{{ Str::limit($al->description, 120) }}</p>
                @endif
                @if(Auth::user()->isInstructor() || Auth::user()->isAdmin())
                    <form action="{{ route('courses.archive.import', [$course, $archive, $al]) }}" method="POST" style="margin-top: 0.5rem;">
                        @csrf
                        <button type="submit" style="padding: 0.35rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.8125rem; font-weight: 600; cursor: pointer;">Use as new lesson (draft)</button>
                    </form>
                @endif
            </div>
        @empty
            <p style="color: #6b7280;">No lessons were stored in this archive.</p>
        @endforelse
    </div>
@endsection
