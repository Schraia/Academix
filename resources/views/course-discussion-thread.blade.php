@extends('course-show-layout')
@section('title', $thread->title . ' - Discussions')
@section('page_heading', 'Discussion')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <a href="{{ route('courses.discussions', $course) }}" class="back-link" style="margin-bottom: 1rem;">← Back to Discussions</a>

    <div class="courses-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">{{ $thread->title }}</h2>
        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $thread->user->name ?? 'User' }} · {{ $thread->created_at->format('M j, Y g:i A') }}</p>
        @if($thread->announcement_id && $thread->announcement)
            <p style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.5rem;">Re: <a href="{{ route('courses.announcements', $course) }}#ann-item-{{ $thread->announcement->id }}" style="color: #dc2626;">{{ $thread->announcement->title }}</a> (announcement by {{ $thread->announcement->user->name ?? 'Instructor' }})</p>
        @endif
        <div style="padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb; font-size: 0.9375rem; color: #374151; line-height: 1.6;">{{ $thread->content }}</div>

        <div style="margin-top: 1.25rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem;">Replies ({{ $thread->messages->count() }})</h3>
            @forelse($thread->messages as $msg)
                <div style="padding: 0.75rem 0; border-bottom: 1px solid #f3f4f6;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #1f2937;">{{ $msg->user->name ?? 'User' }}</p>
                    <p style="font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.25rem;">{{ $msg->created_at->format('M j, Y g:i A') }}</p>
                    <p style="font-size: 0.9375rem; color: #374151; line-height: 1.5;">{{ $msg->content }}</p>
                </div>
            @empty
                <p style="color: #6b7280; font-size: 0.875rem;">No replies yet. Be the first to reply.</p>
            @endforelse
        </div>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 0.75rem;">Reply to this discussion</h3>
            @if(session('success'))<p style="color: #059669; margin-bottom: 0.5rem; font-size: 0.875rem;">{{ session('success') }}</p>@endif
            <form action="{{ route('courses.discussions.messages.store', [$course, $thread]) }}" method="POST">
                @csrf
                <textarea name="content" required rows="4" placeholder="Write your reply..." style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; font-size: 0.9375rem; margin-bottom: 0.75rem;">{{ old('content') }}</textarea>
                @error('content')<p style="color: #dc2626; font-size: 0.875rem; margin-bottom: 0.5rem;">{{ $message }}</p>@enderror
                <button type="submit" style="padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9375rem;">Post reply</button>
            </form>
        </div>
    </div>
@endsection
