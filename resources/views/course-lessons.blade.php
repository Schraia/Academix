@extends('course-show-layout')
@section('title', $course->title . ' - Lessons')
@section('page_heading', 'Lessons')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($course->lessonModules as $lesson)
            <div style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                    <div>
                        <strong>{{ $lesson->title }}</strong>
                        @if($isInstructor)
                            <span style="font-size: 0.75rem; color: #6b7280; font-weight: normal;">({{ $lesson->status }})</span>
                        @endif
                    </div>
                    @if($isInstructor)
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="{{ route('courses.lessons.edit', [$course, $lesson]) }}" style="color: #2563eb; font-size: 0.875rem;">Edit</a>
                            <form action="{{ route('courses.lessons.toggle', [$course, $lesson]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: #2563eb; font-size: 0.875rem; cursor: pointer;">{{ $lesson->status === 'published' ? 'Hide from students' : 'Show to students' }}</button>
                            </form>
                            <form action="{{ route('courses.lessons.destroy', [$course, $lesson]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this lesson?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc2626; font-size: 0.875rem; cursor: pointer;">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
                @if($lesson->description)
                    <p style="margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">{{ Str::limit($lesson->description, 120) }}</p>
                @endif
                @if($lesson->attachment_path)
                    <p style="margin-top: 0.5rem;"><a href="{{ route('courses.lessons.preview', [$course, $lesson]) }}" style="color: #dc2626; font-weight: 600;">See File</a></p>
                @endif
            </div>
        @empty
            <p style="color: #6b7280;">No lessons published yet for this course.</p>
        @endforelse
    </div>
@endsection
