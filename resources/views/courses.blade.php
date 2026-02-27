<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px; min-height: 100vh; flex-shrink: 0;
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
            color: white; display: flex; flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
        .nav-menu { flex: 1; min-height: 0; overflow-y: auto; padding: 1rem 0; }
        .nav-item {
            padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem;
            color: inherit; text-decoration: none; transition: background-color 0.3s;
        }
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .nav-item.active { background-color: rgba(255, 255, 255, 0.2); }
        .nav-item svg { width: 20px; height: 20px; }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.1); position: sticky; bottom: 0; background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); }
        .logout-btn {
            width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1);
            color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px;
            cursor: pointer; font-size: 1rem; font-weight: 600;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .logout-btn:hover { background: rgba(255, 255, 255, 0.2); }
        .logout-btn svg { width: 20px; height: 20px; }
        .main-content { flex: 1; padding: 3rem; }
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem; }
        .page-subtitle { color: #6b7280; margin-bottom: 0.5rem; }
        .college-course-header { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 1.5rem; }
        .courses-card {
            background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .courses-table { width: 100%; border-collapse: collapse; }
        .courses-table th, .courses-table td { padding: 1rem 1.25rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .courses-table th { background: #f9fafb; font-weight: 600; color: #374151; }
        .courses-table tr:last-child td { border-bottom: none; }
        .empty-state { padding: 3rem; text-align: center; color: #6b7280; }
        .empty-state a { color: #ef4444; font-weight: 600; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header"><h2>Academix</h2></div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    <span>Courses</span>
                </a>
                <a href="{{ route('enroll') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    <span>Enroll Online</span>
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
            <h1 class="page-title">My Courses</h1>
            <p class="page-subtitle">School year {{ $schoolYear }}</p>
            @if($collegeCourses->isNotEmpty())
                <p class="college-course-header">Your program: {{ $collegeCourses->pluck('name')->join(', ') }}</p>
            @endif

            @if(session('success'))
                <p style="color: #16a34a; margin-bottom: 1rem;">{{ session('success') }}</p>
            @endif

            <div class="courses-card">
                @if($enrollments->isEmpty() && $courses->isEmpty())
                    <div class="empty-state">
                        <p>You are not enrolled in any courses for this school year.</p>
                        <p style="margin-top: 0.5rem;"><a href="{{ route('enroll') }}">Enroll online</a></p>
                    </div>
                @elseif($enrollments->isEmpty() && Auth::user()->isStudent())
                    <div class="empty-state">
                        <p>You are not enrolled in any courses. Use the list below to open a course.</p>
                    </div>
                @else
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Section</th>
                                <th>Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(Auth::user()->isInstructor())
                                @forelse($courses as $course)
                                    <tr>
                                        <td>{{ $course->code }}</td>
                                        <td><a href="{{ route('courses.show', $course) }}">{{ $course->title }}</a></td>
                                        <td>Instructor</td>
                                        <td>{{ Auth::user()->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">You have not been assigned any courses.</td>
                                    </tr>
                                @endforelse
                            @else
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->course->code }}</td>
                                        <td><a href="{{ route('courses.show', $enrollment->course) }}">{{ $enrollment->course->title }}</a></td>
                                        <td>{{ $enrollment->section_name }}</td>
                                        <td>{{ $enrollment->course->instructor_name ?? 'TBA' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="empty-state">No courses found for your enrollment.</td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>

            @if(isset($allCourses) && $allCourses->isNotEmpty())
                <h2 class="page-title" style="margin-top: 2rem; font-size: 1.25rem;">All courses</h2>
                <p class="page-subtitle" style="margin-bottom: 0.75rem;">Open any course to manage content (instructors).</p>
                <div class="courses-card">
                    <table class="courses-table">
                        <thead>
                            <tr><th>Course</th><th>Code</th></tr>
                        </thead>
                        <tbody>
                            @foreach($allCourses as $c)
                                <tr>
                                    <td><a href="{{ route('courses.show', $c) }}" style="color: #dc2626; text-decoration: underline;">{{ $c->title }}</a></td>
                                    <td>{{ $c->code ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
