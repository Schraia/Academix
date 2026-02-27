@extends('course-show-layout')
@section('title', $course->title . ' - Upload Announcement')
@section('page_heading', 'Upload Announcement')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group textarea { min-height: 160px; resize: vertical; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.upload.announcements.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(request('return_to'))<input type="hidden" name="return_to" value="{{ request('return_to') }}">@endif
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
                @error('title') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="content">Content *</label>
                <textarea id="content" name="content" required>{{ old('content') }}</textarea>
                @error('content') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="image">Image (optional)</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                <p class="hint" style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">JPEG, PNG, GIF, or WebP. Max 10MB.</p>
                @error('image') <p class="error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Add announcement</button>
        </form>
    </div>
@endsection
