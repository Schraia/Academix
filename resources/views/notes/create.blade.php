<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Note - Academix</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.1.12/dist/trix.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%); }
        .wrap { max-width: 1100px; margin: 2.5rem auto; padding: 0 1.25rem; }
        .card { background: white; border-radius: 18px; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1rem; }
        .title { font-size: 1.8rem; font-weight: 800; color: #0f172a; }
        .subtitle { color: #64748b; margin-top: 0.35rem; font-weight: 600; }
        .btn-outline { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.2rem; border-radius: 999px; font-weight: 700; font-size: 0.9rem; border: 1px solid #dc2626; color: #dc2626; background: white; transition: all 0.25s ease; text-decoration: none; }
        .btn-outline:hover { background: #dc2626; color: white; box-shadow: 0 6px 16px rgba(220,38,38,0.25); transform: translateY(-2px); }
        .form-row { display: grid; grid-template-columns: 1fr 320px; gap: 1rem; }
        @media (max-width: 980px) { .form-row { grid-template-columns: 1fr; } }
        label { font-weight: 800; font-size: 0.85rem; color: #0f172a; display: block; margin-bottom: 0.4rem; }
        input[type="text"] { width: 100%; padding: 0.75rem 0.9rem; border: 1px solid #e2e8f0; border-radius: 12px; font-weight: 700; }
        .select { width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0.6rem 0.75rem; min-height: 140px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.65rem 1rem; border-radius: 12px; font-weight: 800; font-size: 0.9rem; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; text-decoration: none; color: #0f172a; }
        .btn-primary { border-color: #dc2626; background: #dc2626; color: white; }
        .btn-primary:hover { background: #991b1b; }
        .btn:hover { background: #f8fafc; }
        .actions { display:flex; gap:0.75rem; flex-wrap: wrap; margin-top: 1rem; }
        .alert-error { background: #fef2f2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        trix-editor { border-radius: 14px; border: 1px solid #e2e8f0; min-height: 320px; }
        .note-tools { display:flex; gap:0.5rem; flex-wrap: wrap; margin: 0.75rem 0 0.5rem; }
        .tool-btn { border: 1px solid #e2e8f0; background: #fff; border-radius: 999px; padding: 0.5rem 0.85rem; font-weight: 800; cursor: pointer; }
        .tool-btn:hover { background: #f8fafc; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: none; align-items: center; justify-content: center; z-index: 50; padding: 1.25rem; }
        .modal { background: white; border-radius: 16px; padding: 1rem; width: 100%; max-width: 860px; border: 1px solid #e2e8f0; box-shadow: 0 25px 60px rgba(0,0,0,0.2); }
        .modal-head { display:flex; justify-content: space-between; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
        .modal-title { font-weight: 900; color: #0f172a; }
        canvas { width: 100%; height: 380px; border-radius: 14px; border: 1px solid #e2e8f0; background: #fff; touch-action: none; }
        .modal-actions { display:flex; gap:0.5rem; flex-wrap: wrap; justify-content: flex-end; margin-top: 0.75rem; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <div>
            <div class="title">New note</div>
            <div class="subtitle">Format text, add images, or insert a quick drawing.</div>
        </div>
        <a href="{{ route('notes.index') }}" class="btn-outline">← Back</a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('notes.store') }}">
            @csrf
            <div class="form-row">
                <div>
                    <div style="margin-bottom: 0.9rem;">
                        <label for="title">Title</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="e.g., Midterm review notes" required>
                    </div>

                    <label>Note</label>
                    <div class="note-tools">
                        <button type="button" class="tool-btn" id="btn-insert-image">Insert image</button>
                        <button type="button" class="tool-btn" id="btn-open-draw">Add drawing</button>
                    </div>

                    <input id="content_html" type="hidden" name="content_html" value="{{ old('content_html') }}">
                    <trix-editor input="content_html"></trix-editor>
                    <input type="file" id="image-input" accept="image/*" style="display:none;">
                </div>

                <div>
                    <label for="course_ids">Tags (courses)</label>
                    <select id="course_ids" class="select" name="course_ids[]" multiple>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ collect(old('course_ids', []))->contains($c->id) ? 'selected' : '' }}>
                                {{ $c->code ? $c->code . ' — ' : '' }}{{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                    <div style="margin-top:0.6rem; color:#64748b; font-weight:600; font-size:0.85rem;">
                        Tip: You can tag a note to multiple courses.
                    </div>
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Save note</button>
                <a href="{{ route('notes.index') }}" class="btn">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="draw-overlay" aria-hidden="true">
    <div class="modal">
        <div class="modal-head">
            <div class="modal-title">Free-hand drawing</div>
            <button type="button" class="btn" id="draw-close">Close</button>
        </div>
        <canvas id="draw-canvas" width="1200" height="520"></canvas>
        <div class="modal-actions">
            <button type="button" class="btn" id="draw-clear">Clear</button>
            <button type="button" class="btn btn-primary" id="draw-insert">Insert into note</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/trix@2.1.12/dist/trix.umd.min.js"></script>
<script>
    (function () {
        const csrf = @json(csrf_token());
        const uploadUrl = @json(route('notes.attachments.upload'));

        async function uploadFile(file) {
            const fd = new FormData();
            fd.append('file', file);
            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd
            });
            if (!res.ok) throw new Error('Upload failed');
            return await res.json();
        }

        document.addEventListener('trix-attachment-add', async (event) => {
            const attachment = event.attachment;
            if (!attachment || !attachment.file) return;
            try {
                const data = await uploadFile(attachment.file);
                attachment.setAttributes({ url: data.url, href: data.url });
            } catch (e) {
                console.error(e);
                attachment.remove();
                alert('Upload failed. Please try again.');
            }
        });

        const imgBtn = document.getElementById('btn-insert-image');
        const imgInput = document.getElementById('image-input');
        imgBtn?.addEventListener('click', () => imgInput.click());
        imgInput?.addEventListener('change', () => {
            if (!imgInput.files || !imgInput.files[0]) return;
            const editor = document.querySelector('trix-editor');
            editor?.editor?.insertFile(imgInput.files[0]);
            imgInput.value = '';
        });

        // Simple freehand drawing (pointer events)
        const overlay = document.getElementById('draw-overlay');
        const canvas = document.getElementById('draw-canvas');
        const ctx = canvas?.getContext('2d');
        let drawing = false;
        let last = null;

        function openDraw() { overlay.style.display = 'flex'; overlay.setAttribute('aria-hidden', 'false'); }
        function closeDraw() { overlay.style.display = 'none'; overlay.setAttribute('aria-hidden', 'true'); }

        function canvasPoint(e) {
            const rect = canvas.getBoundingClientRect();
            const x = (e.clientX - rect.left) * (canvas.width / rect.width);
            const y = (e.clientY - rect.top) * (canvas.height / rect.height);
            return { x, y };
        }

        function start(e) {
            drawing = true;
            last = canvasPoint(e);
        }
        function move(e) {
            if (!drawing || !ctx) return;
            const p = canvasPoint(e);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#0f172a';
            ctx.lineWidth = 6;
            ctx.beginPath();
            ctx.moveTo(last.x, last.y);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            last = p;
        }
        function end() { drawing = false; last = null; }

        document.getElementById('btn-open-draw')?.addEventListener('click', openDraw);
        document.getElementById('draw-close')?.addEventListener('click', closeDraw);
        overlay?.addEventListener('click', (e) => { if (e.target === overlay) closeDraw(); });
        document.getElementById('draw-clear')?.addEventListener('click', () => { ctx?.clearRect(0, 0, canvas.width, canvas.height); });

        canvas?.addEventListener('pointerdown', start);
        canvas?.addEventListener('pointermove', move);
        window.addEventListener('pointerup', end);

        document.getElementById('draw-insert')?.addEventListener('click', async () => {
            if (!canvas) return;
            const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));
            if (!blob) return;
            const file = new File([blob], 'drawing.png', { type: 'image/png' });

            try {
                const data = await uploadFile(file);
                const editor = document.querySelector('trix-editor');
                editor?.editor?.insertHTML(`<img src="${data.url}" alt="Drawing" />`);
                closeDraw();
            } catch (e) {
                console.error(e);
                alert('Drawing upload failed. Please try again.');
            }
        });
    })();
</script>
</body>
</html>

