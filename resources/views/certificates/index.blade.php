<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates - Academix</title>
    @vite('resources/css/app.css')
    <style>
        /* ── Base ───────────────────────────────────── */
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background:#f3f4f6; }

        /* ── Layout ─────────────────────────────────── */
        .dashboard-container { display:flex; min-height:100vh; }
        .main-content { flex:1; padding:2rem 3rem; background:
            radial-gradient(circle at 10% 10%, rgba(185,28,28,0.18), transparent 50%),
            radial-gradient(circle at 90% 30%, rgba(220,38,38,0.15), transparent 50%),
            linear-gradient(180deg, #ffffff 0%, #f3f4f6 100%);
        }

        /* ── Sidebar ───────────────────────────────── */
        .sidebar { width:260px; height:100vh; position:sticky; top:0; flex-shrink:0; color:rgba(255,255,255,0.92);
            display:flex; flex-direction:column; overflow:hidden;
            background:linear-gradient(180deg, #962121 0%, #991b1b 40%, #450a0a 100%);
            box-shadow:8px 0 40px rgba(0,0,0,0.35);
        }
        .sidebar::after { content:""; position:absolute; inset:0; pointer-events:none; background:
            radial-gradient(circle at 20% 10%, rgba(255,255,255,0.05), transparent 40%),
            radial-gradient(circle at 80% 30%, rgba(255,255,255,0.04), transparent 40%);
        }
        .sidebar::before { content:""; position:absolute; top:0; right:0; width:3px; height:100%; opacity:.3;
            background:linear-gradient(to bottom, rgba(255,255,255,0.5), transparent);
        }
        .sidebar-header { padding:2rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; justify-content:center; align-items:center; }
        .sidebar-logo { max-width:140px; filter:drop-shadow(0 6px 12px rgba(0,0,0,0.4)); }

        /* ── Navigation ────────────────────────────── */
        .nav-menu { flex:1; min-height:0; overflow-y:auto; padding:1rem 0; }
        .nav-item { padding:0.9rem 1.75rem; display:flex; align-items:center; gap:0.9rem; cursor:pointer;
            font-weight:500; font-size:0.95rem; position:relative; transition:all 0.25s ease;
        }
        .nav-item svg { width:19px; height:19px; opacity:.85; transition:all 0.25s ease; }
        .nav-item:hover { background:rgba(255,255,255,0.08); padding-left:2.1rem; }
        .nav-item:hover svg { opacity:1; transform:scale(1.15); }
        .nav-item.active { background:rgba(255,255,255,0.12); }
        .nav-item.active::before { content:""; position:absolute; left:0; top:0; height:100%; width:6px;
            background:linear-gradient(180deg, #ef4444, #ffffff);
            border-radius:0 6px 6px 0;
        }
        .nav-logout { margin-top:auto; padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.08); }
        .logout-btn { width:100%; padding:0.75rem; background:rgba(255,255,255,0.1); color:#fff;
            border:1px solid rgba(255,255,255,0.2); border-radius:10px; cursor:pointer; font-size:1rem; font-weight:600;
            display:flex; align-items:center; justify-content:center; gap:0.5rem; transition:all 0.3s ease;
        }
        .logout-btn:hover { background:#fff; color:#b91c1c; transform:translateY(-2px); }
        .logout-btn svg { width:20px; height:20px; }

        /* ── Typography ───────────────────────────── */
        .page-title { font-size:1.75rem; font-weight:700; color:#1f2937; margin-bottom:0.25rem; }
        .page-subtitle { font-size:0.9375rem; color:#6b7280; margin-bottom:1.5rem; }

        /* ── Cards + Lists ────────────────────────── */
        .card { background:#fff; border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.07); padding:1.5rem; margin-bottom:1rem; }
        .empty-state { color:#6b7280; font-size:0.9375rem; padding:1.5rem 0; }

        .courses-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:1rem; }
        .course-card { display:block; background:#fff; border-radius:12px; padding:1.25rem 1.5rem; text-decoration:none; color:inherit;
            border:1px solid #e5e7eb; box-shadow:0 4px 6px rgba(0,0,0,0.07); transition:box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        }
        .course-card:hover { box-shadow:0 8px 16px rgba(0,0,0,0.1); transform:translateY(-2px); border-color:#dc2626; }
        .course-card h3 { font-size:1.1rem; font-weight:600; color:#1f2937; margin-bottom:0.5rem; }
        .course-card .cert-count { font-size:0.875rem; color:#6b7280; display:flex; align-items:center; gap:0.35rem; }
        .course-card .cert-count svg { width:16px; height:16px; color:#dc2626; }

        /* ── Actions + Modals ─────────────────────── */
        .btn-issue-cert { display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; margin-bottom:1rem;
            background:#dc2626; color:#fff; border:none; border-radius:8px; font-weight:600; font-size:0.875rem; cursor:pointer;
        }
        .btn-issue-cert:hover { background:#b91c1c; color:#fff; }

        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:100; }
        .modal-overlay[hidden] { display:none; }
        .modal-box { background:#fff; border-radius:12px; padding:1.5rem; min-width:320px; max-width:90vw; max-height:80vh; overflow-y:auto; }
        .modal-box h3 { font-size:1.125rem; margin-bottom:1rem; color:#1f2937; }
        .modal-course-list { list-style:none; }
        .modal-course-list li { margin-bottom:0.5rem; }
        .modal-course-list a { display:block; padding:0.6rem 0.75rem; border-radius:8px; color:#1f2937; text-decoration:none; border:1px solid #e5e7eb; }
        .modal-course-list a:hover { background:#fef2f2; border-color:#dc2626; color:#dc2626; }
        .modal-close { margin-top:1rem; padding:0.5rem 1rem; background:#e5e7eb; border:none; border-radius:8px; cursor:pointer; font-size:0.875rem; }
        .modal-close:hover { background:#d1d5db; }

        /* ── History Table ────────────────────────── */
        .history-table { width:100%; border-collapse:collapse; font-size:0.875rem; }
        .history-table th { text-align:left; color:#6b7280; font-weight:600; padding:0.5rem 0.25rem; border-bottom:1px solid #e5e7eb; }
        .history-table td { padding:0.6rem 0.25rem; border-bottom:1px solid #f3f4f6; color:#111827; }
        .history-table .muted { color:#6b7280; font-size:0.8125rem; }
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
                @if(!Auth::user()->isAdmin() && !Auth::user()->isInstructor())
                <a href="{{ route('enroll') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    <span>Enroll Online</span>
                </a>
                @endif
                <a href="{{ route('inbox.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"> <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/> <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    <span>Inbox</span>
                </a>
                <div class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                    <span>Certificates</span>
                </div>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('settings.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                    <span>Admin Panel</span>
                </a>
                @endif
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
            <h1 class="page-title">Certificates</h1>
            <p class="page-subtitle">Courses for which an instructor has issued you a certificate. Click a course to view and download.</p>

            @if(session('success'))
            <div class="card" style="border-left:4px solid #16a34a;">
                <p style="color:#166534; font-size:0.9375rem;">{{ session('success') }}</p>
            </div>
            @endif

            @if(Auth::user()->isInstructor() && $instructorCourses->isNotEmpty())
            <button type="button" class="btn-issue-cert" id="openCertModalBtn">Upload / Issue certificate</button>
            @endif

            @if(Auth::user()->isInstructor())
            <div class="card">
                <h3 style="font-size:1rem; font-weight:700; color:#111827; margin-bottom:.35rem;">Issued History</h3>
                <p style="font-size:.75rem; color:#9ca3af; margin-bottom:0.75rem;">Certificates issued for your courses.</p>
                @if($issuedCertificates->isEmpty())
                    <p class="empty-state" style="padding:0;">No certificates issued yet.</p>
                @else
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Issued</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issuedCertificates as $issued)
                                <tr>
                                    <td>
                                        <div>{{ $issued->user?->name ?? 'Unknown' }}</div>
                                        @if($issued->user?->email)
                                            <div class="muted">{{ $issued->user->email }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $issued->course?->title ?? 'Unknown' }}</td>
                                    <td>
                                        <div>{{ optional($issued->issued_date)->format('F j, Y') }}</div>
                                        <div class="muted">#{{ $issued->certificate_number }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @endif

            @if($courses->isEmpty() && !Auth::user()->isInstructor())
            <div class="card">
                <p class="empty-state">No certificates have been issued to you yet. Certificates will appear here once an instructor issues one for a course you’re enrolled in.</p>
            </div>
            @else
            <div class="courses-grid">
                @foreach($courses as $course)
                <a href="{{ route('certificates.show', $course) }}" class="course-card">
                    <h3>{{ $course->title }}</h3>
                    <span class="cert-count">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                        {{ $course->certificates_count }} {{ Str::plural('certificate', $course->certificates_count) }}
                    </span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @if(Auth::user()->isInstructor() && $instructorCourses->isNotEmpty())
    <div class="modal-overlay" id="certModal" hidden>
        <div class="modal-box">
            <h3>Choose course to issue certificate from</h3>
            <ul class="modal-course-list">
                @foreach($instructorCourses as $course)
                <li><a href="{{ route('courses.upload.certificates', $course) }}">{{ $course->title }}{{ $course->code ? ' (' . $course->code . ')' : '' }}</a></li>
                @endforeach
            </ul>
            <button type="button" class="modal-close" id="closeCertModalBtn">Close</button>
        </div>
    </div>
    <script>
    (function(){
        var btn = document.getElementById('openCertModalBtn');
        var modal = document.getElementById('certModal');
        var closeBtn = document.getElementById('closeCertModalBtn');
        if (btn && modal) {
            btn.addEventListener('click', function() { modal.hidden = false; });
            if (closeBtn) closeBtn.addEventListener('click', function() { modal.hidden = true; });
            modal.addEventListener('click', function(e) { if (e.target === modal) modal.hidden = true; });
        }
    })();
    </script>
    @endif
</body>
</html>
