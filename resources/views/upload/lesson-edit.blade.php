@extends('course-show-layout')
@section('title', $course->title . ' - Edit Lesson')
@section('page_heading', 'Edit Lesson')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
                @error('title') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $lesson->description) }}</textarea>
            </div>
            <div class="form-group">
                <label for="content">Content (optional)</label>
                <textarea id="content" name="content" style="min-height: 120px;">{{ old('content', $lesson->content) }}</textarea>
            </div>
            <div class="form-group">
                <label for="attachment">File (optional)</label>
                @if($lesson->attachment_path)
                    <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem;">Current file is attached. Upload a new file to replace it.</p>
                @endif
                <input type="file" id="attachment" name="attachment" accept=".pdf,.pptx,.docx,.png,application/pdf,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/png">
                <p class="hint" style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;">PDF, PPTX, DOCX, or PNG. Max 50MB.</p>
                @error('attachment') <p class="error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Update lesson</button>
        </form>
    </div>
@endsection
