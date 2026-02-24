@extends('course-show-layout')
@section('title', $course->title . ' - Announcements')
@section('page_heading', 'Announcements')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($announcements ?? [] as $a)
            <div class="announcement-item" style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
                <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                    <strong>{{ $a->title }}</strong>
                    <button type="button" class="btn-toggle-announcement" data-target="ann-content-{{ $a->id }}" aria-expanded="true" style="padding: 0.2rem 0.5rem; font-size: 0.8rem; color: #dc2626; background: none; border: 1px solid #dc2626; border-radius: 6px; cursor: pointer;">Hide</button>
                    @if($isInstructor ?? false)
                    <a href="{{ route('courses.announcements.edit', [$course, $a]) }}" style="font-size: 0.8rem; color: #6b7280;">Edit</a>
                    <form action="{{ route('courses.announcements.toggle', [$course, $a]) }}" method="POST" style="display: inline;">@csrf<button type="submit" style="padding: 0; font-size: 0.8rem; color: #6b7280; background: none; border: none; cursor: pointer;">{{ $a->is_visible ? 'Hide from students' : 'Show to students' }}</button></form>
                    <form action="{{ route('courses.announcements.destroy', [$course, $a]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this announcement?');">@csrf @method('DELETE')<button type="submit" style="font-size: 0.8rem; color: #dc2626; background: none; border: none; cursor: pointer;">Delete</button></form>
                    @endif
                </div>
                <p style="margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">{{ $a->user->name ?? 'Instructor' }} · {{ $a->created_at->format('M j, Y') }}</p>
                <div id="ann-content-{{ $a->id }}" class="announcement-content">
                    @if($a->image_path)
                        <p style="margin-top: 0.5rem;"><img src="{{ asset('storage/' . $a->image_path) }}" alt="" style="max-width: 100%; height: auto; border-radius: 8px;"></p>
                    @endif
                    <p style="margin-top: 0.5rem; font-size: 0.9375rem;">{{ $a->content }}</p>
                </div>
            </div>
        @empty
            <p style="color: #6b7280;">No announcements for this course yet.</p>
        @endforelse
    </div>
    <script>
    document.querySelectorAll('.btn-toggle-announcement').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var el = document.getElementById(this.getAttribute('data-target'));
            if (!el) return;
            var isHidden = el.hidden;
            el.hidden = !isHidden;
            this.textContent = isHidden ? 'Hide' : 'Show';
            this.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });
    });
    </script>
@endsection
