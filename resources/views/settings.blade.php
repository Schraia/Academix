<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; min-height: 100vh; flex-shrink: 0; background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); color: white; display: flex; flex-direction: column; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
        .nav-menu { flex: 1; padding: 1rem 0; }
        .nav-item { padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: inherit; text-decoration: none; transition: background-color 0.3s; }
        .nav-item:hover { background-color: rgba(255,255,255,0.1); }
        .nav-item.active { background-color: rgba(255,255,255,0.2); }
        .nav-item svg { width: 20px; height: 20px; }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .logout-btn { width: 100%; padding: 1rem 1.5rem; background: transparent; color: white; border: none; cursor: pointer; font-size: 1rem; display: flex; align-items: center; gap: 0.75rem; text-align: left; }
        .logout-btn:hover { background-color: rgba(255,255,255,0.1); }
        .logout-btn svg { width: 20px; height: 20px; }
        .main-content { flex: 1; padding: 2rem 3rem; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
        .card table { width: 100%; border-collapse: collapse; }
        .card th, .card td { padding: 1rem 1.25rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .card th { background: #f9fafb; font-weight: 600; color: #374151; }
        .card tr:last-child td { border-bottom: none; }
        .role-form { display: flex; align-items: center; gap: 0.5rem; }
        .role-form select { padding: 0.35rem 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
        .role-form button { padding: 0.35rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; }
        .role-form button:hover { background: #b91c1c; }
        .course-form { display: flex; align-items: center; gap: 0.5rem; }
        .course-form select { padding: 0.35rem 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
        .course-form button { padding: 0.35rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; }
        .course-form button:hover { background: #b91c1c; }
        .badge-role { font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; }
        .badge-student { background: #e5e7eb; color: #374151; }
        .badge-instructor { background: #dbeafe; color: #1d4ed8; }
        .badge-admin { background: #fce7f3; color: #be185d; }
        .alert-success { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header"><h2>Academix</h2></div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg><span>Dashboard</span></a>
                <a href="{{ route('courses.index') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg><span>Courses</span></a>
                <a href="{{ route('settings.index') }}" class="nav-item active"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg><span>Settings</span></a>
                <a href="{{ route('enroll') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg><span>Enroll Online</span></a>
            </nav>
            <div class="nav-logout">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="logout-btn"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg><span>Logout</span></button>
                </form>
            </div>
        </div>
        <div class="main-content">
            <h1 class="page-title">Settings</h1>
            <p style="color: #6b7280; margin-bottom: 1rem;">Set user roles. You can make an existing user an <strong>Instructor</strong> or <strong>Admin</strong>.</p>
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current role</th>
                            <th>Set role</th>
                            <th>Assigned Courses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="badge-role badge-admin">Admin</span>
                                    @elseif($u->role === 'instructor')
                                        <span class="badge-role badge-instructor">Instructor</span>
                                    @else
                                        <span class="badge-role badge-student">Student</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('settings.updateRole') }}" method="POST" class="role-form">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <select name="role">
                                            <option value="student" {{ $u->role === 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="instructor" {{ $u->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td>
                                    @if($u->role === 'instructor')
                                        <form action="{{ route('settings.assignCourses') }}" method="POST" class="course-form">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                                            <select name="course_id">
                                                <option value="">Select a course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ $u->collegeCourses->contains($course) ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit">Assign</button>
                                        </form>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
