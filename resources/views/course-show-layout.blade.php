<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $course->title ?? 'Course') - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; min-height: 100vh; flex-shrink: 0; background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1); }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
        .nav-menu { flex: 1; min-height: 0; overflow-y: auto; padding: 1rem 0; }
        .nav-item { padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: inherit; text-decoration: none; transition: background-color 0.3s; }
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .nav-item.active { background-color: rgba(255, 255, 255, 0.2); }
        .nav-item svg { width: 20px; height: 20px; }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); }
        .logout-btn { width: 100%; padding: 1rem 1.5rem; background: transparent; color: white; border: none; cursor: pointer; font-size: 1rem; display: flex; align-items: center; gap: 0.75rem; text-align: left; }
        .logout-btn:hover { background-color: rgba(255, 255, 255, 0.1); }
        .logout-btn svg { width: 20px; height: 20px; }
        .main-content { flex: 1; padding: 2rem 3rem; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem; }
        .page-subtitle { color: #6b7280; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #dc2626; font-weight: 600; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .courses-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .courses-table { width: 100%; border-collapse: collapse; }
        .courses-table th, .courses-table td { padding: 1rem 1.25rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .courses-table th { background: #f9fafb; font-weight: 600; color: #374151; }
        .courses-table tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Academix</h2>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg><span>Dashboard</span></a>
                <a href="{{ route('courses.index') }}" class="nav-item active"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg><span>Courses</span></a>
                <a href="#" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg><span>Profile</span></a>
                <a href="{{ route('enroll') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg><span>Enroll Online</span></a>
                <a href="#" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg><span>Certificates</span></a>
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
            <a href="{{ route('courses.show', $course) }}" class="back-link">← Back to {{ $course->title }}</a>
            <h1 class="page-title">@yield('page_heading', 'Course')</h1>
            @yield('content')
        </div>
    </div>
</body>
</html>
