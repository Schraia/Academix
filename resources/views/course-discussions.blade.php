@extends('course-show-layout')
@section('title', $course->title . ' - Discussions')
@section('page_heading', 'Discussions')
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1.5rem;">
        @forelse($threads as $thread)
            <div style="padding: 1rem 0; border-bottom: 1px solid #e5e7eb;">
                <strong>{{ $thread->title }}</strong>
                <p style="margin-top: 0.25rem; color: #6b7280; font-size: 0.875rem;">{{ $thread->user->name ?? 'User' }} · {{ $thread->created_at->format('M j, Y') }}</p>
                <p style="margin-top: 0.5rem; font-size: 0.9375rem;">{{ Str::limit($thread->content, 200) }}</p>
            </div>
        @empty
            <p style="color: #6b7280;">No discussions yet for this course.</p>
        @endforelse
        @if($threads->hasPages())
            <div style="margin-top: 1rem;">{{ $threads->links() }}</div>
        @endif
    </div>
@endsection
