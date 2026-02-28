@extends('course-show-layout')
@section('title', $course->title . ' - Issue Certificate')
@section('page_heading', 'Issue Certificate')
@section('content')
    <style>
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; max-width: 560px; }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-group input, .form-group select { width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; }
        .form-group .hint { font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; }
        .btn-submit { padding: 0.6rem 1.25rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #b91c1c; }
        .error { color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem; }
    </style>
    <div class="form-card">
        <form action="{{ route('courses.upload.certificates.store', $course) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="user_id">Student *</label>
                <select id="user_id" name="user_id" required>
                    <option value="">Select student</option>
                    @foreach($enrolledUsers as $e)
                        <option value="{{ $e->user_id }}" {{ old('user_id', $prefillUserId ?? '') == $e->user_id ? 'selected' : '' }}>{{ $e->user->name }} ({{ $e->user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="issued_date">Issued date *</label>
                <input type="date" id="issued_date" name="issued_date" value="{{ old('issued_date', now()->format('Y-m-d')) }}" required>
                @error('issued_date') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry date (optional)</label>
                <input type="date" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                @error('expiry_date') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="form-group">
                <label for="certificate_file">Certificate file (PDF or image)</label>
                <input type="file" id="certificate_file" name="certificate_file" accept=".pdf,.png,.jpg,.jpeg">
                <p class="hint">Optional. PDF, PNG, or JPG. Max 10MB. The student will be able to download it from their Certificates page.</p>
                @error('certificate_file') <p class="error">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-submit">Issue certificate</button>
        </form>
    </div>
@endsection
