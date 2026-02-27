@extends('course-show-layout')
@section('title', $course->title . ' - Lessons')
@section('page_heading', 'Lessons')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    @if(!$isInstructor && isset($totalLessons) && $totalLessons > 0)
    <div class="courses-card" style="padding: 1rem 1.5rem; margin-bottom: 1rem;">
        <p style="font-size: 0.875rem; color: #374151; margin-bottom: 0.5rem;">Your progress: {{ $completedCount ?? 0 }} of {{ $totalLessons }} {{ Str::plural('module', $totalLessons) }} @if(($completedCount ?? 0) >= $totalLessons) · You're caught up! @endif</p>
        <div style="height: 10px; background: #e5e7eb; border-radius: 5px; overflow: hidden;">
            <div style="height: 100%; width: {{ $totalLessons > 0 ? round(($completedCount ?? 0) / $totalLessons * 100) : 0 }}%; background: linear-gradient(90deg, #22c55e, #16a34a); border-radius: 5px; transition: width 0.3s;"></div>
        </div>
    </div>
    @endif
    @if($isInstructor)
    <p style="margin-bottom: 1rem;"><a href="{{ route('courses.upload.lessons', $course) }}?return_to=lessons" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.9375rem;">Upload Lesson</a></p>
    @endif
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($course->lessonModules as $lesson)
            <div style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; flex-wrap: wrap;">
                    <div>
                        <strong>{{ $lesson->title }}</strong>
                        @if($lesson->published_at)
                            <span style="font-size: 0.75rem; color: #6b7280; font-weight: normal;"> · Published {{ $lesson->published_at->format('M j, Y') }}</span>
                        @endif
                        @if($isInstructor)
                            <span style="font-size: 0.75rem; color: #6b7280; font-weight: normal;"> ({{ $lesson->status }})</span>
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
                    <p style="margin-top: 0.5rem;">
                        <a href="{{ route('courses.lessons.preview', [$course, $lesson]) }}" style="color: #dc2626; font-weight: 600;">See File</a>
                        @if(!$isInstructor)
                            <form action="{{ route('courses.lessons.progress.toggle', [$course, $lesson]) }}" method="POST" style="display: inline; margin-left: 1rem;">
                                @csrf
                                <input type="hidden" name="completed" value="0">
                                <label style="font-size: 0.875rem; color: #374151; cursor: pointer;">
                                    <input type="checkbox" name="completed" value="1" {{ in_array($lesson->id, $completedLessonIds ?? []) ? 'checked' : '' }} onchange="this.form.submit()"> Mark as done
                                </label>
                            </form>
                        @endif
                    </p>
                @endif
            </div>
        @empty
            <p style="color: #6b7280;">No lessons published yet for this course.</p>
        @endforelse
    </div>
@endsection
