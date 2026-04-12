<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive access — {{ $instructor->email }}</title>
    @vite('resources/css/app.css')
    <style>
        body { font-family: system-ui, sans-serif; background: #f3f4f6; padding: 2rem; }
        .card { background: white; max-width: 720px; margin: 0 auto; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.08); }
        h1 { font-size: 1.25rem; margin-bottom: 0.5rem; }
        p { color: #6b7280; margin-bottom: 1rem; font-size: 0.9rem; }
        label { display: flex; gap: 0.5rem; align-items: flex-start; padding: 0.5rem 0; border-bottom: 1px solid #f3f4f6; cursor: pointer; }
        .btn { margin-top: 1rem; padding: 0.55rem 1.2rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        a.back { color: #dc2626; font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <a href="{{ route('settings.index') }}" class="back">← Admin Panel</a>
    <h1 style="margin-top: 1rem;">Course archive access</h1>
    <p>Instructor: <strong>{{ $instructor->name ?: $instructor->email }}</strong></p>
    <p>Checked = <strong>blocked</strong> (they will not see that archive for the listed course). Unchecked = can use that archive when assigned to the course.</p>
    <form action="{{ route('settings.instructorArchiveAccess.save', $instructor) }}" method="POST">
        @csrf
        @forelse($archives as $a)
            <label>
                <input type="checkbox" name="blocked_archive_ids[]" value="{{ $a->id }}" {{ in_array($a->id, $blockedIds, true) ? 'checked' : '' }}>
                <span><strong>{{ $a->label }}</strong><br><span style="color:#6b7280;font-size:0.85rem;">{{ $a->course?->code }} {{ $a->course?->title }}</span></span>
            </label>
        @empty
            <p>No archives exist for this instructor’s assigned courses yet.</p>
        @endforelse
        @if($archives->isNotEmpty())
            <button type="submit" class="btn">Save</button>
        @endif
    </form>
</div>
</body>
</html>
