@extends('course-show-layout')
@section('title', $course->title . ' - Announcements')
@section('page_heading', 'Announcements')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($announcements ?? [] as $a)
            <div id="ann-item-{{ $a->id }}" class="announcement-item" style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
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
                <p style="margin-top: 0.25rem;">
                    <a href="{{ route('courses.discussions', [$course, 'reply_announcement' => $a->id]) }}" class="reply-link" style="font-size: 0.8125rem; color: #6b7280; text-decoration: none;">Reply</a>
                </p>
                <div id="ann-content-{{ $a->id }}" class="announcement-content">
                    @if($a->image_path)
                        <p style="margin-top: 0.5rem;">
                            <button type="button" class="ann-image-thumb" data-full-src="{{ asset('storage/' . $a->image_path) }}" data-filename="{{ basename($a->image_path) }}" style="padding: 0; border: none; background: none; cursor: pointer; border-radius: 8px; display: block;">
                                <img src="{{ asset('storage/' . $a->image_path) }}" alt="" style="max-width: 320px; max-height: 240px; width: auto; height: auto; border-radius: 8px; display: block;">
                            </button>
                        </p>
                    @endif
                    <p style="margin-top: 0.5rem; font-size: 0.9375rem;">{{ $a->content }}</p>
                </div>
            </div>
        @empty
            <p style="color: #6b7280;">No announcements for this course yet.</p>
        @endforelse
    </div>
    <div id="ann-image-modal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 1000; align-items: center; justify-content: center; padding: 1rem;" aria-hidden="true">
        <div style="background: white; border-radius: 12px; max-width: 90vw; max-height: 90vh; overflow: auto; padding: 1rem; position: relative;">
            <button type="button" id="ann-image-modal-close" style="position: absolute; top: 0.5rem; right: 0.5rem; padding: 0.25rem; background: #e5e7eb; border: none; border-radius: 6px; cursor: pointer; font-size: 1.25rem; line-height: 1;">×</button>
            <img id="ann-image-modal-img" src="" alt="" style="max-width: 100%; height: auto; display: block; margin-bottom: 0.75rem;">
            <a id="ann-image-modal-download" href="" download="" style="display: inline-block; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">Download image</a>
        </div>
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
    var modal = document.getElementById('ann-image-modal');
    var modalImg = document.getElementById('ann-image-modal-img');
    var modalDownload = document.getElementById('ann-image-modal-download');
    document.querySelectorAll('.ann-image-thumb').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var src = this.getAttribute('data-full-src');
            var filename = this.getAttribute('data-filename') || 'image';
            modalImg.src = src;
            modalDownload.href = src;
            modalDownload.download = filename;
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        });
    });
    document.getElementById('ann-image-modal-close').addEventListener('click', function() {
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
    });
    modal.addEventListener('click', function(e) {
        if (e.target === modal) { modal.style.display = 'none'; modal.setAttribute('aria-hidden', 'true'); }
    });
    </script>
@endsection
