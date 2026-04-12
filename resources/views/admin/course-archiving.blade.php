<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Archiving - Admin</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; height: 100vh; position: sticky; top: 0; flex-shrink: 0; background: linear-gradient(180deg, #962121 0%, #991b1b 40%, #450a0a 100%); color: rgba(255,255,255,0.92); display: flex; flex-direction: column; box-shadow: 8px 0 40px rgba(0,0,0,0.35); overflow: hidden; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: center; align-items: center; }
        .sidebar-logo { max-width: 140px; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4)); }
        .nav-menu { flex: 1; overflow-y: auto; padding: 1rem 0; }
        .nav-item { padding: 0.9rem 1.75rem; display: flex; align-items: center; gap: 0.9rem; font-weight: 500; font-size: 0.95rem; text-decoration: none; color: inherit; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.08); }
        .nav-item.active { background: rgba(255,255,255,0.12); }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); }
        .logout-btn { width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: 600; }
        .main-content { flex: 1; padding: 2rem 3rem; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
        .card h2 { font-size: 1.15rem; margin-bottom: 1rem; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-weight: 600; color: #374151; font-size: 0.875rem; }
        .btn { padding: 0.45rem 0.9rem; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; font-size: 0.875rem; }
        .btn-danger { background: #fef2f2; color: #b91c1c; }
        .btn-primary { background: #dc2626; color: white; }
        input[type="text"], select { padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; width: 100%; max-width: 420px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        form.inline { display: inline; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header"><img src="{{ asset('images/logo.png') }}" alt="Logo" class="sidebar-logo"></div>
        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>
            <a href="{{ route('settings.index') }}" class="nav-item">Admin Panel</a>
            <a href="{{ route('settings.courseArchiving') }}" class="nav-item active">Course Archiving</a>
        </nav>
        <div class="nav-logout">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    <div class="main-content">
        <h1 class="page-title" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">Course Archiving</h1>
        <p style="color: #6b7280; margin-bottom: 1rem;">Archive published lessons for a timeframe. Instructors can browse archives from each course and copy lessons into the current term.</p>
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

        <div class="card">
            <h2>Archive a course (snapshot of published lessons)</h2>
            <form action="{{ route('settings.courseArchiving.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 1rem; max-width: 520px;">
                @csrf
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:0.35rem;font-size:0.875rem;">Course</label>
                    <select name="course_id" required>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->code ? $c->code . ' — ' : '' }}{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;font-weight:600;margin-bottom:0.35rem;font-size:0.875rem;">Timeframe label</label>
                    <input type="text" name="label" required placeholder='e.g. A.Y. 2026-2027 December - April' maxlength="255">
                </div>
                <button type="submit" class="btn btn-primary" style="align-self: flex-start;">Create archive</button>
            </form>
            <form method="GET" action="{{ route('settings.courseArchiving') }}" style="margin-top: 1.25rem;">
                <label style="font-weight:600;font-size:0.875rem;">Search courses</label>
                <div style="display:flex;gap:0.5rem;margin-top:0.35rem;flex-wrap:wrap;">
                    <input type="text" name="q" value="{{ $searchQ }}" placeholder="Code or name">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Previous archives</h2>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Timeframe</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archives as $a)
                        <tr>
                            <td>{{ $a->course?->code ? $a->course->code . ' — ' : '' }}{{ $a->course?->title ?? '—' }}</td>
                            <td>{{ $a->label }}</td>
                            <td>{{ $a->created_at->format('M j, Y') }}</td>
                            <td>
                                <form class="inline" action="{{ route('settings.courseArchiving.destroy', $a) }}" method="POST" onsubmit="return confirm('Delete this archive and its stored lesson files?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="color:#6b7280;">No archives yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="margin-top: 1rem;">{{ $archives->links() }}</div>
        </div>
    </div>
</div>
</body>
</html>
