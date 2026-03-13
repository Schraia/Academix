@extends('course-show-layout')
@section('title', $course->title . ' - Issue Certificate')
@section('page_heading', 'Issue Certificate')
@section('content')
<style>
    /* ── Form controls ──────────────────────────── */
    .field-label  { display:block; font-size:.8125rem; font-weight:600; color:#374151; margin-bottom:5px; }
    .field-ctrl   { width:100%; padding:.45rem .7rem; font-size:.9375rem; border:1.5px solid #d1d5db; border-radius:8px; background:#fff; color:#111827; outline:none; transition:border-color .15s, box-shadow .15s; }
    .field-ctrl:focus { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.1); }
    .field-hint   { font-size:.75rem; color:#6b7280; margin-top:4px; }
    .field-error  { font-size:.8rem;  color:#dc2626; margin-top:4px; }

    /* ── Card section dividers ──────────────────── */
    .form-section { padding:1.1rem 1.4rem; }
    .form-section + .form-section { border-top:1px solid #f0f0f0; }

    /* ── Template picker ────────────────────────── */
    .tmpl-btn { display:block; width:100%; text-align:left; background:#fff; border:1.5px solid #e5e7eb; border-radius:10px; padding:10px; cursor:pointer; transition:border-color .2s, box-shadow .2s; }
    .tmpl-btn:hover  { border-color:#9ca3af; }
    .tmpl-btn.active { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,.12); }
    .tmpl-swatch { border-radius:6px; height:64px; border:1px solid #e5e7eb; margin-bottom:7px; }
    .tmpl-swatch.t1 { background:linear-gradient(135deg,#fffef7,#f2e8c9); }
    .tmpl-swatch.t2 { background:linear-gradient(135deg,#f8fafc,#e2e8f0); }
    .tmpl-swatch.t3 { background:linear-gradient(135deg,#111827,#334155); }
    .tmpl-swatch.t4 { background:linear-gradient(135deg,#fff8eb,#f7d9a8); }

    /* ── Submit button ──────────────────────────── */
    .btn-issue { padding:.55rem 1.5rem; background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:.9375rem; cursor:pointer; transition:background .15s; }
    .btn-issue:hover { background:#b91c1c; }

    /* ── Saved signature box ────────────────────── */
    .sig-box { border:1.5px solid #e5e7eb; border-radius:8px; padding:.75rem 1rem; background:#fafafa; margin-bottom:10px; }
    .sig-box img { max-height:44px; max-width:180px; object-fit:contain; }

    /* ── Responsive two-column layout ──────────────── */
    .cert-builder-grid { display:grid; grid-template-columns:1fr 0.85fr; gap:1.5rem; align-items:start; min-width:0; }
    .cert-builder-grid > * { min-width:0; }
    .preview-col       { position:sticky; top:1.5rem; }
    @media (max-width: 1280px) {
        .cert-builder-grid { grid-template-columns:1fr; }
        .preview-col       { position:static; }
    }

    /* ── Inner two-column rows (Student/Signer, Dates) ─ */
    .inner-2col { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
    @media (max-width: 640px) {
        .inner-2col { grid-template-columns:1fr; }
    }

    /* ── Subtitle textarea ──────────────────────────── */
    textarea.field-ctrl { resize:vertical; min-height:72px; line-height:1.5; }
</style>

<div class="cert-builder-grid">

    {{-- ─── LEFT: Form card ─── --}}
    <div style="background:#fff; border-radius:14px; border:1px solid #e5e7eb; box-shadow:0 4px 18px rgba(0,0,0,.06); overflow:hidden;">

        <div style="padding:1.1rem 1.4rem; border-bottom:1px solid #f0f0f0;">
            <p style="margin:0; font-size:1rem; font-weight:700; color:#111827;">Certificate Details</p>
            <p style="margin:.2rem 0 0; font-size:.8125rem; color:#6b7280;">Fill in the fields below, choose a template, and preview on the right.</p>
        </div>

        <form id="certificateIssueForm"
              action="{{ route('courses.upload.certificates.store', $course) }}"
              method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Student + Signer --}}
            <div class="form-section inner-2col">
                <div>
                    <label for="user_id" class="field-label">Student <span style="color:#dc2626">*</span></label>
                    <select id="user_id" name="user_id" class="field-ctrl" required>
                        <option value="">Select student…</option>
                        @foreach($enrolledUsers as $e)
                            @if($e->user)
                                <option value="{{ $e->user_id }}" {{ old('user_id', $prefillUserId ?? '') == $e->user_id ? 'selected' : '' }}>
                                    {{ $e->user->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('user_id') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="signer_name" class="field-label">Signer Name <span style="color:#dc2626">*</span></label>
                    <input type="text" id="signer_name" name="signer_name" class="field-ctrl"
                           value="{{ old('signer_name', $instructor->name) }}" required>
                    @error('signer_name') <p class="field-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Subtitle + Dates --}}
            <div class="form-section">
                <div style="margin-bottom:.75rem;">
                    <label for="subtitle" class="field-label">Subtitle</label>
                    <textarea id="subtitle" name="subtitle" class="field-ctrl" maxlength="255" rows="3"
                              placeholder="Appears below the awardee name on the certificate">{{ old('subtitle', 'In recognition of successfully completing the course.') }}</textarea>
                    @error('subtitle') <p class="field-error">{{ $message }}</p> @enderror
                </div>
                <div class="inner-2col">
                    <div>
                        <label for="issued_date" class="field-label">Issued Date <span style="color:#dc2626">*</span></label>
                        <input type="date" id="issued_date" name="issued_date" class="field-ctrl"
                               value="{{ old('issued_date', now()->format('Y-m-d')) }}" required>
                        @error('issued_date') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="expiry_date" class="field-label">Expiry Date <span style="font-weight:400; color:#9ca3af;">(optional)</span></label>
                        <input type="date" id="expiry_date" name="expiry_date" class="field-ctrl"
                               value="{{ old('expiry_date') }}">
                        @error('expiry_date') <p class="field-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Digital Signature --}}
            <div class="form-section">
                <p class="field-label" style="margin-bottom:8px;">Digital Signature</p>

                @if(!empty($instructor->signature_path))
                    <div class="sig-box">
                        <div style="display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                            <img src="{{ Storage::disk('public')->url($instructor->signature_path) }}" alt="Saved signature">
                            <span style="font-size:.8125rem; color:#6b7280;">Saved signature on file.</span>
                        </div>
                        <label style="display:flex; align-items:center; gap:.5rem; margin-top:8px; font-size:.8125rem; cursor:pointer; color:#374151;">
                            <input type="checkbox" name="use_saved_signature" value="1"
                                   {{ old('use_saved_signature', '1') ? 'checked' : '' }}>
                            Use this saved signature
                        </label>
                    </div>
                @else
                    <input type="hidden" name="use_saved_signature" value="0">
                @endif

                <label for="digital_signature" class="field-label" style="font-weight:500;">Upload new signature</label>
                <input type="file" id="digital_signature" name="digital_signature" class="field-ctrl"
                       style="padding:.35rem .7rem;" accept=".png,.jpg,.jpeg">
                <p class="field-hint">PNG or JPG, max 2 MB. Uploading replaces your saved signature for all future certificates.</p>
                @error('digital_signature') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Template picker --}}
            <div class="form-section">
                <p class="field-label" style="margin-bottom:10px;">Template <span style="color:#dc2626">*</span></p>
                <input type="hidden" id="template_id" name="template_id" value="{{ old('template_id', 1) }}">
                @php $selectedTemplate = (int) old('template_id', 1); @endphp
                <div id="templatePicker" style="display:grid; grid-template-columns:1fr 1fr; gap:.6rem;">
                    @foreach($templateOptions as $id => $template)
                        <button type="button"
                                class="tmpl-btn {{ $selectedTemplate === $id ? 'active' : '' }}"
                                data-template-id="{{ $id }}">
                            <div class="tmpl-swatch t{{ $id }}"></div>
                            <div style="font-size:.875rem; font-weight:600; color:#111827;">{{ $template['name'] }}</div>
                            <div style="font-size:.75rem; color:#6b7280; margin-top:2px;">{{ $template['description'] }}</div>
                        </button>
                    @endforeach
                </div>
                @error('template_id') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            {{-- Footer / submit --}}
            <div style="padding:1rem 1.4rem; border-top:1px solid #f0f0f0; display:flex; justify-content:flex-end;">
                <button type="submit" class="btn-issue">Issue Certificate</button>
            </div>

        </form>
    </div>

    {{-- ─── RIGHT: Preview card (sticky) ─── --}}
    <div class="preview-col">
        <div style="background:#fff; border-radius:14px; border:1px solid #e5e7eb; box-shadow:0 4px 18px rgba(0,0,0,.06); overflow:hidden;">

            <div style="padding:.875rem 1.25rem; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:.9375rem; font-weight:700; color:#111827;">Live Preview</span>
                <span style="font-size:.75rem; color:#9ca3af;">Updates as you type</span>
            </div>

            <div style="padding:.75rem; background:#f3f4f6;">
                {{-- Wrapper: JS fills this width and scales the iframe to fit --}}
                <div id="previewScaler" style="width:100%; overflow:hidden; border-radius:8px; border:1px solid #e5e7eb; background:#fff;">
                    <iframe id="certificatePreviewFrame"
                            style="width:1123px; height:794px; border:none; display:block; transform-origin:top left; pointer-events:none;"
                            title="Certificate preview"></iframe>
                </div>
            </div>

            <div style="padding:.6rem 1.25rem;">
                <p style="font-size:.75rem; color:#9ca3af; margin:0;">Uploaded signatures will appear in the preview only after saving.</p>
            </div>

        </div>
    </div>

</div>

<script>
(function () {
    var form          = document.getElementById('certificateIssueForm');
    var frame         = document.getElementById('certificatePreviewFrame');
    var scaler        = document.getElementById('previewScaler');
    var picker        = document.getElementById('templatePicker');
    var templateInput = document.getElementById('template_id');
    var previewBase   = "{{ route('courses.upload.certificates.preview', $course) }}";
    var debounceTimer = null;

    /* Scale the iframe to exactly fill the preview wrapper width */
    function resizePreview() {
        if (!scaler || !frame) return;
        var scale = scaler.offsetWidth / 1123;
        frame.style.transform = 'scale(' + scale + ')';
        scaler.style.height   = Math.round(794 * scale) + 'px';
    }

    function updatePreview() {
        if (!form || !frame) return;
        var params = new URLSearchParams();
        ['user_id', 'template_id', 'signer_name', 'subtitle', 'issued_date', 'expiry_date'].forEach(function (name) {
            var el = form.elements[name];
            if (el && el.value !== '') params.set(name, el.value);
        });
        frame.src = previewBase + '?' + params.toString();
    }

    function queue() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(updatePreview, 300);
    }

    /* Template picker clicks */
    if (picker && templateInput) {
        picker.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-template-id]');
            if (!btn) return;
            templateInput.value = btn.getAttribute('data-template-id');
            picker.querySelectorAll('.tmpl-btn').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            queue();
        });
    }

    if (form) {
        form.addEventListener('input',  queue);
        form.addEventListener('change', queue);
    }

    window.addEventListener('resize', resizePreview);
    resizePreview();   /* set initial scale */
    updatePreview();   /* load initial preview */
})();
</script>
@endsection
