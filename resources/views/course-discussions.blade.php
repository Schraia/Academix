@extends('course-show-layout')
@section('title', $course->title . ' - Discussions')
@section('page_heading', 'Discussions')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>

    <div style="margin-bottom: 1rem;">
        <button type="button" id="btn-new-discussion" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9375rem;">New Discussion</button>
    </div>

    <div id="new-discussion-container" class="courses-card" style="padding: 1.5rem; margin-bottom: 1.5rem; display: {{ (old('title') || $replyTitle ?? null) ? 'block' : 'none' }};">
        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.75rem;">Start a discussion</h3>
        <form action="{{ route('courses.discussions.store', $course) }}" method="POST">
            @csrf
            @if(!empty($replyAnnouncementId))<input type="hidden" name="announcement_id" value="{{ $replyAnnouncementId }}">@endif
            <div style="margin-bottom: 0.75rem;">
                <label for="discussion-title" style="display: block; font-size: 0.875rem; color: #374151; margin-bottom: 0.25rem;">Title</label>
                <input type="text" name="title" id="discussion-title" value="{{ old('title', $replyTitle ?? '') }}" required maxlength="255" placeholder="Discussion title" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9375rem;">
                @error('title')<p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <div style="margin-bottom: 0.75rem;">
                <label for="discussion-content" style="display: block; font-size: 0.875rem; color: #374151; margin-bottom: 0.25rem;">Content</label>
                <textarea name="content" id="discussion-content" required rows="4" placeholder="Write your message..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9375rem;">{{ old('content') }}</textarea>
                @error('content')<p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>@enderror
            </div>
            <button type="submit" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9375rem;">Post discussion</button>
        </form>
    </div>

    <div class="courses-card" style="padding: 1.5rem;">
        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem;">Discussions</h3>
        @if(session('success'))<p style="color: #059669; margin-bottom: 0.75rem; font-size: 0.9375rem;">{{ session('success') }}</p>@endif
        @forelse($threads as $thread)
            <a href="{{ route('courses.discussions.thread', [$course, $thread]) }}" style="display: block; text-decoration: none; color: inherit; padding: 1rem 0; border-bottom: 1px solid #e5e7eb; transition: background 0.15s;" class="thread-link" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                <strong style="color: #1f2937;">{{ $thread->title }}</strong>
                <p style="margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">{{ $thread->user->name ?? 'User' }} · {{ $thread->created_at->format('M j, Y') }}@if($thread->messages_count > 0) · {{ $thread->messages_count }} {{ Str::plural('reply', $thread->messages_count) }}@endif</p>
                @if($thread->announcement_id && $thread->announcement)
                    <p style="margin-top: 0.25rem; font-size: 0.8125rem; color: #6b7280;">Re: {{ $thread->announcement->title }} (announcement by {{ $thread->announcement->user->name ?? 'Instructor' }})</p>
                @endif
                <p style="margin-top: 0.5rem; font-size: 0.9375rem; color: #4b5563;">{{ Str::limit($thread->content, 200) }}</p>
            </a>
        @empty
            <p style="color: #6b7280;">No discussions yet for this course.</p>
        @endforelse
        @if($threads->hasPages())
            <div style="margin-top: 1rem;">{{ $threads->links() }}</div>
        @endif
    </div>
    <script>
    document.getElementById('btn-new-discussion').addEventListener('click', function() {
        var el = document.getElementById('new-discussion-container');
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    });
    </script>
@endsection
