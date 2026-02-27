@extends('course-show-layout')
@section('title', $lesson->title . ' - Preview')
@section('page_heading', $lesson->title)
@section('content')
    <p class="page-subtitle" style="margin-bottom: 1rem;">{{ $course->code ?? $course->title }}</p>
    <div class="courses-card" style="padding: 1rem 1.5rem;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
            <a href="{{ route('courses.lessons', $course) }}" class="back-link">← Back to lessons</a>
            <a href="{{ $fileUrl }}" download="{{ $downloadFilename ?? basename($lesson->attachment_path ?? '') }}" class="btn-download" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #dc2626; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem;">Download file</a>
        </div>
        @if($canPreview)
            @if($extension === 'pdf')
                <div style="position: relative;">
                    <button type="button" id="btn-fullscreen" style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10; padding: 0.4rem 0.75rem; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Fullscreen</button>
                    <iframe id="preview-iframe" src="{{ $fileUrl }}#toolbar=1" style="width: 100%; height: 75vh; border: 1px solid #e5e7eb; border-radius: 8px;" title="PDF preview"></iframe>
                </div>
                <div id="fullscreen-overlay" style="display: none; position: fixed; inset: 0; background: #000; z-index: 9999; flex-direction: column;">
                    <div style="padding: 0.5rem 1rem; background: rgba(0,0,0,0.8); display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: white; font-size: 0.9375rem;">{{ $lesson->title }}</span>
                        <button type="button" id="btn-exit-fullscreen" style="padding: 0.4rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Exit fullscreen</button>
                    </div>
                    <iframe id="fullscreen-iframe" src="{{ $fileUrl }}#toolbar=1" style="flex: 1; width: 100%; border: none;" title="PDF preview"></iframe>
                </div>
            @else
                <div style="position: relative;">
                    <button type="button" id="btn-fullscreen" style="position: absolute; top: 0.5rem; right: 0.5rem; z-index: 10; padding: 0.4rem 0.75rem; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Fullscreen</button>
                    <img id="preview-image" src="{{ $fileUrl }}" alt="{{ $lesson->title }}" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #e5e7eb;">
                </div>
                <div id="fullscreen-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.95); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
                    <button type="button" id="btn-exit-fullscreen" style="position: absolute; top: 1rem; right: 1rem; padding: 0.4rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">Exit fullscreen</button>
                    <img src="{{ $fileUrl }}" alt="{{ $lesson->title }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
            @endif
        @else
            <p style="color: #6b7280; margin-bottom: 0.5rem;">Preview is not available for this file type. Use the button above to download and open it on your device.</p>
            <a href="{{ $fileUrl }}" download="{{ $downloadFilename ?? basename($lesson->attachment_path ?? '') }}" style="color: #dc2626; font-weight: 600;">Download file</a>
        @endif
    </div>
    @if($canPreview)
    <script>
    (function() {
        var overlay = document.getElementById('fullscreen-overlay');
        var btnFs = document.getElementById('btn-fullscreen');
        var btnExit = document.getElementById('btn-exit-fullscreen');
        if (!overlay || !btnFs) return;
        btnFs.addEventListener('click', function() {
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
        if (btnExit) btnExit.addEventListener('click', function() {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        });
        overlay.addEventListener('click', function(e) { if (e.target === overlay) { overlay.style.display = 'none'; document.body.style.overflow = ''; } });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && overlay.style.display === 'flex') { overlay.style.display = 'none'; document.body.style.overflow = ''; } });
    })();
    </script>
    @endif
@endsection
