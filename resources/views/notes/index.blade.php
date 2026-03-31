<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Notes - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%); }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; height: 100vh; position: sticky; top: 0; flex-shrink: 0; background: linear-gradient(180deg, #962121 0%, #991b1b 40%, #450a0a 100%); color: rgba(255,255,255,0.92); display: flex; flex-direction: column; box-shadow: 8px 0 40px rgba(0,0,0,0.35); overflow: hidden; }
        .sidebar::after { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at 20% 10%, rgba(255,255,255,0.05), transparent 40%), radial-gradient(circle at 80% 30%, rgba(255,255,255,0.04), transparent 40%); pointer-events: none; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: center; align-items: center; }
        .sidebar-logo { max-width: 140px; filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4)); }
        .nav-menu { flex: 1; overflow-y: auto; padding: 1rem 0; }
        .nav-item { padding: 0.9rem 1.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.9rem; font-weight: 500; font-size: 0.95rem; position: relative; transition: all 0.25s ease; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.08); padding-left: 2.10rem; }
        .nav-item svg { width: 19px; height: 19px; opacity: 0.85; transition: all 0.25s ease; }
        .nav-item:hover svg { opacity: 1; transform: scale(1.15); }
        .nav-item.active { background: rgba(255,255,255,0.12); }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.08); }
        .logout-btn { width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .logout-btn:hover { background: white; color: #b91c1c; transform: translateY(-2px); }
        .main-content { flex: 1; padding: 3rem 4rem; overflow-y: auto; background: radial-gradient(circle at 10% 10%, rgba(185,28,28,0.18), transparent 50%), radial-gradient(circle at 90% 30%, rgba(220,38,38,0.15), transparent 50%), linear-gradient(180deg, #ffffff 0%, #f3f4f6 100%); }
        .page-header { background: white; padding: 1.75rem 2rem; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .page-title { font-size: 2.2rem; font-weight: 700; letter-spacing: -0.03em; color: #0f172a; }
        .page-subtitle { color: #6b7280; font-weight: 500; margin-top: 0.6rem; }
        .btn-outline { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1.2rem; border-radius: 999px; font-weight: 600; font-size: 0.85rem; border: 1px solid #dc2626; color: #dc2626; background: white; transition: all 0.25s ease; text-decoration: none; }
        .btn-outline:hover { background: #dc2626; color: white; box-shadow: 0 6px 16px rgba(220,38,38,0.25); transform: translateY(-2px); }
        .alert-success { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .alert-error { background: #fef2f2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .card { background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%); border-radius: 22px; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 8px 20px rgba(15,23,42,0.04), 0 25px 50px rgba(15,23,42,0.05); margin-bottom: 1rem; }
        .toolbar { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .select { padding: 0.55rem 0.8rem; border-radius: 12px; border: 1px solid #e2e8f0; background: white; font-weight: 600; color: #0f172a; }
        .notes-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
        @media (max-width: 1100px) { .notes-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 780px) { .notes-grid { grid-template-columns: 1fr; } .main-content { padding: 2rem 1.25rem; } }
        .note-card { background: white; border: 1px solid #e2e8f0; border-radius: 18px; padding: 1.25rem; box-shadow: 0 12px 30px rgba(15,23,42,0.05); }
        .note-title { font-weight: 800; color: #0f172a; font-size: 1.05rem; margin-bottom: 0.35rem; }
        .note-meta { color: #64748b; font-size: 0.85rem; margin-bottom: 0.75rem; }
        .badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 999px; background: #fee2e2; color: #991b1b; font-weight: 700; font-size: 0.75rem; margin-right: 0.35rem; margin-top: 0.35rem; }
        .note-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.9rem; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.55rem 0.9rem; border-radius: 12px; font-weight: 700; font-size: 0.85rem; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; text-decoration: none; color: #0f172a; }
        .btn:hover { background: #f8fafc; }
        .btn-danger { border-color: #fecaca; background: #fef2f2; color: #991b1b; }
        .btn-danger:hover { background: #fee2e2; }
        .btn-primary { border-color: #dc2626; background: #dc2626; color: white; }
        .btn-primary:hover { background: #991b1b; }
        .download-menu { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .small { font-size: 0.85rem; color: #64748b; }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header"><img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo"></div>
        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('courses.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                <span>Courses</span>
            </a>
            <a href="{{ route('profile.show') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                <span>Profile</span>
            </a>
            <div class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v11a2 2 0 002 2h12a2 2 0 002-2V7.5a2 2 0 00-.586-1.414l-2.5-2.5A2 2 0 0014.5 3H4z"/></svg>
                <span>Private Notes</span>
            </div>
            <a href="{{ route('inbox.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"> <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/> <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                <span>Inbox</span>
            </a>
            <a href="{{ route('certificates.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                <span>Certificates</span>
            </a>
        </nav>
        <div class="nav-logout">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="logout-btn">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="page-header">
            <div>
                <div class="page-title">Private Notes</div>
                <div class="page-subtitle">Create notes, tag them by course, and download them anytime.</div>
                <div class="small" style="margin-top: 0.6rem;">Stored: <strong>{{ $notesCount }}</strong> / <strong>{{ $notesLimit }}</strong></div>
            </div>
            <div class="toolbar">
                <form method="GET" action="{{ route('notes.index') }}" style="display:flex; gap:0.5rem; align-items:center; flex-wrap: wrap;">
                    <select name="course_id" class="select" aria-label="Filter by course">
                        <option value="">All tags</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ (string)$selectedCourseId === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->code ? $c->code . ' — ' : '' }}{{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn" type="submit">Filter</button>
                    <a class="btn" href="{{ route('notes.index') }}">Reset</a>
                </form>
                <a href="{{ route('notes.create') }}" class="btn-outline">+ New note</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            @if($notes->isEmpty())
                <div class="small">No notes yet. Create your first one.</div>
            @else
                <div class="notes-grid">
                    @foreach($notes as $note)
                        <div class="note-card">
                            <div class="note-title">{{ $note->title }}</div>
                            <div class="note-meta">Updated {{ $note->updated_at?->diffForHumans() }}</div>
                            <div>
                                @forelse($note->courses as $c)
                                    <span class="badge">{{ $c->code ?: $c->title }}</span>
                                @empty
                                    <span class="small">No tags</span>
                                @endforelse
                            </div>
                            <div class="note-actions">
                                <a class="btn btn-primary" href="{{ route('notes.edit', $note) }}">Edit</a>
                                <div class="download-menu">
                                    <a class="btn" href="{{ route('notes.download', [$note, 'txt']) }}">TXT</a>
                                    <a class="btn" href="{{ route('notes.download', [$note, 'docx']) }}">DOCS</a>
                                    <a class="btn" href="{{ route('notes.download', [$note, 'pdf']) }}">PDF</a>
                                </div>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}" onsubmit="return confirm('Delete this note?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>

