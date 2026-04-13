@extends('course-show-layout')
@section('title', $course->title . ' - Edit')
@section('page_heading', 'Edit course')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; width: 100%; max-width: none; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group textarea { min-height: 140px; resize: vertical; }
        .form-group .hint { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
        .banner-preview-wrap { margin-top: 0.5rem; height: 360px; overflow: hidden; border-radius: 12px; border: 1px solid #e5e7eb; background: #f9fafb; }
        .banner-preview-wrap img { display: block; max-width: 100%; width: 100%; }
        .crop-hint { font-size: 0.8125rem; color: #6b7280; margin-top: 0.35rem; }
        .crop-controls { margin-top: .5rem; display:flex; align-items:center; gap:.75rem; }
    </style>
    <div class="form-card">
        <form id="course-edit-form" action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="banner">Banner / course image</label>
                <input type="file" id="banner" name="banner" accept="image/jpeg,image/png,image/gif,image/webp">
                <input type="hidden" name="banner_object_position" id="banner_object_position" value="{{ old('banner_object_position', $course->banner_object_position) }}">
                <input type="hidden" name="banner_crop_data" id="banner_crop_data">
                <p class="hint">Upload to preview. Drag the crop box to choose what stays centered on the course page (banner uses cover crop).</p>
                <p class="crop-hint">Leave empty to keep the current file. Max 10MB.</p>
                <div id="banner-crop-container" class="banner-preview-wrap" style="display: none;">
                    <img id="banner-crop-img" alt="Banner preview">
                </div>
                <div class="crop-controls" id="crop-controls" style="display:none;">
                    <button type="button" id="zoom-out" class="btn-submit" style="padding:.35rem .7rem;">-</button>
                    <input type="range" id="zoom-range" min="0" max="100" value="0" style="flex:1;">
                    <button type="button" id="zoom-in" class="btn-submit" style="padding:.35rem .7rem;">+</button>
                </div>
                @if($course->banner_path)
                    <p class="hint" style="margin-top: 0.75rem;">Current banner (how it appears now):</p>
                    <div class="banner-preview-wrap" style="max-height: 140px;">
                        <img src="{{ asset('storage/' . $course->banner_path) }}" alt="" style="width: 100%; height: 140px; object-fit: cover; object-position: {{ $course->banner_object_position ?: 'center' }};">
                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    (function() {
        var input = document.getElementById('banner');
        var img = document.getElementById('banner-crop-img');
        var wrap = document.getElementById('banner-crop-container');
        var posField = document.getElementById('banner_object_position');
        var form = document.getElementById('course-edit-form');
        var cropDataField = document.getElementById('banner_crop_data');
        var zoomRange = document.getElementById('zoom-range');
        var zoomIn = document.getElementById('zoom-in');
        var zoomOut = document.getElementById('zoom-out');
        var controls = document.getElementById('crop-controls');
        var cropper = null;
        function updatePosition() {
            if (!cropper || !posField) return;
            var d = cropper.getData(true);
            var natural = cropper.getImageData();
            if (!natural.naturalWidth) return;
            var cx = d.x + d.width / 2;
            var cy = d.y + d.height / 2;
            var px = Math.min(100, Math.max(0, (cx / natural.naturalWidth) * 100));
            var py = Math.min(100, Math.max(0, (cy / natural.naturalHeight) * 100));
            posField.value = px.toFixed(1) + '% ' + py.toFixed(1) + '%';
        }
        if (input && img && wrap) {
            input.addEventListener('change', function() {
                var f = input.files && input.files[0];
                if (!f) {
                    wrap.style.display = 'none';
                    if (cropper) { cropper.destroy(); cropper = null; }
                    return;
                }
                var url = URL.createObjectURL(f);
                img.onload = function() {
                    URL.revokeObjectURL(url);
                    wrap.style.display = 'block';
                    if (controls) controls.style.display = 'flex';
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(img, {
                        aspectRatio: 16 / 9,
                        viewMode: 1,
                        autoCropArea: 0.85,
                        movable: true,
                        zoomable: true,
                        scalable: false,
                        crop: function() { updatePosition(); }
                    });
                    updatePosition();
                };
                img.src = url;
            });
        }
        if (form) {
            form.addEventListener('submit', function() {
                if (cropper) {
                    updatePosition();
                    if (cropDataField) {
                        var d = cropper.getData(true);
                        cropDataField.value = JSON.stringify({
                            x: d.x || 0,
                            y: d.y || 0,
                            width: d.width || 0,
                            height: d.height || 0
                        });
                    }
                }
            });
        }
        if (zoomRange) {
            zoomRange.addEventListener('input', function () {
                if (!cropper) return;
                cropper.zoomTo(0.1 + (Number(this.value) / 100) * 1.9);
                updatePosition();
            });
        }
        if (zoomIn) {
            zoomIn.addEventListener('click', function () { if (cropper) cropper.zoom(0.1); });
        }
        if (zoomOut) {
            zoomOut.addEventListener('click', function () { if (cropper) cropper.zoom(-0.1); });
        }
    })();
    </script>
@endsection
