<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Enrollments - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar {
    width: 250px;
    height: 100vh;
    position: sticky;
    top: 0;
    flex-shrink: 0;
    background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
    color: white;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25);
    overflow: hidden;
} 
.sidebar::before { content: ''; position: absolute; top: 0; right: 0; width: 3px; height: 100%; background: linear-gradient(to bottom, rgba(255,255,255,0.5), transparent); opacity: 0.3; }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: center; align-items: center; }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
        .nav-menu { flex: 1; min-height: 0; overflow-y: auto; padding: 1rem 0; }
        .nav-item { padding: 1rem 1.5rem; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.75rem; position: relative; font-weight: 500; border-left: 4px solid transparent; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.08); padding-left: 1.75rem; }
        .nav-item:hover svg { transform: scale(1.1); }
        .nav-item svg { width: 20px; height: 20px; transition: all 0.3s ease; }
        .nav-item.active { background: rgba(255, 255, 255, 0.15); border-left: 4px solid white; }
        .nav-logout {
    margin-top: auto;
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.08);
}
        .logout-btn { width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .logout-btn:hover { background: white; color: #b91c1c; transform: translateY(-2px); }
        .logout-btn svg { width: 20px; height: 20px; }
        .main-content { flex: 1; padding: 2rem 3rem; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem; }
        .page-subtitle { font-size: 0.9375rem; color: #6b7280; margin-bottom: 1.5rem; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); padding: 1.5rem; margin-bottom: 1.5rem; }
        .card h3 { font-size: 1.1rem; font-weight: 600; color: #374151; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #e5e7eb; color: #374151; text-decoration: none; border-radius: 8px; font-size: 0.875rem; font-weight: 500; margin-bottom: 1rem; }
        .btn-back:hover { background: #d1d5db; }
        .enrollments-table { width: 100%; border-collapse: collapse; }
        .enrollments-table th, .enrollments-table td { padding: 0.75rem 0.5rem; text-align: left; border-bottom: 1px solid #f3f4f6; }
        .enrollments-table th { font-weight: 600; color: #6b7280; font-size: 0.875rem; }
        .enrollments-table td { font-size: 0.9375rem; }
        .enrollments-table a { color: #dc2626; text-decoration: none; font-weight: 500; }
        .enrollments-table a:hover { text-decoration: underline; }
        .col-units { text-align: center; width: 5rem; }
        .col-grade { text-align: right; width: 6rem; font-weight: 600; color: #1f2937; }
        .units-badge { display: inline-block; background: #e5e7eb; color: #4b5563; padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.8125rem; }
        .empty-state { color: #6b7280; font-size: 0.9375rem; padding: 1rem 0; }
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
                <a href="{{ route('profile.show') }}" class="nav-item active" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('enroll') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    <span>Enroll Online</span>
                </a>
                <a href="{{ route('certificates.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                    <span>Certificates</span>
                </a>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('settings.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                    <span>Settings</span>
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
            <a href="{{ route('profile.show') }}" class="btn-back">← Back to Profile</a>
            <h1 class="page-title">All enrollments</h1>
            <p class="page-subtitle">Courses you’ve been enrolled in, by year and semester. Click a course to open it.</p>

            @if(count($grouped) > 0)
                @foreach($grouped as $group)
                    <div class="card">
                        <h3>{{ $group['label'] }}</h3>
                        <table class="enrollments-table">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th class="col-units">Units</th>
                                    <th class="col-grade">Weighted grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group['items'] as $item)
                                    <tr>
                                        <td>
                                            @if($item['course'])
                                                <a href="{{ route('courses.show', $item['course']) }}">{{ $item['course']->title }}</a>
                                                @if($item['course']->code)
                                                    <span style="font-size: 0.8125rem; color: #6b7280;">{{ $item['course']->code }}</span>
                                                @endif
                                            @else
                                                {{ $item['enrollment']->course_name ?? '—' }}
                                            @endif
                                        </td>
                                        <td class="col-units">
                                            @if($item['units'] !== null)
                                                <span class="units-badge">{{ $item['units'] }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="col-grade">
                                            @if($item['weighted_grade'] !== null)
                                                {{ $item['weighted_grade'] }}%
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            @else
                <div class="card">
                    <p class="empty-state">You have no enrollment history yet. Enroll in courses from Enroll Online to see them here.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>