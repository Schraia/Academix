@extends('course-show-layout')
@section('title', $course->title . ' - Edit')
@section('page_heading', 'Edit course')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group textarea { min-height: 140px; resize: vertical; }
        .form-group .hint { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="banner">Banner / placeholder image</label>
                <input type="file" id="banner" name="banner" accept="image/jpeg,image/png,image/gif,image/webp">
                <p class="hint">Leave empty to keep current. Max 10MB. JPEG, PNG, GIF, WebP.</p>
                @if($course->banner_path)
                    <p class="hint" style="margin-top: 0.5rem;">Current: <img src="{{ asset('storage/' . $course->banner_path) }}" alt="" style="max-width: 200px; max-height: 80px; object-fit: contain; vertical-align: middle;"></p>
                @endif
                @error('banner') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $course->description) }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Save changes</button>
        </form>
    </div>
@endsection
