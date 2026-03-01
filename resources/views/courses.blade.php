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
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
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

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom, rgba(255,255,255,0.5), transparent);
            opacity: 0.3;
        }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: center; align-items: center; }
        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        .nav-menu {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 1rem 0;
        }
        .nav-item {
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            padding-left: 1.75rem;
        }

        .nav-item:hover svg {
            transform: scale(1.1);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            transition: all 0.3s ease;
        }

        .nav-item.active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 4px solid white;
        }
        .nav-item svg {
            width: 20px;
            height: 20px;
        }
        .nav-logout {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            position: sticky;
            bottom: 0;
            background: transparent; 
        }
        .logout-btn {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .logout-btn:hover {
            background: white;
            color: #b91c1c;
            transform: translateY(-2px);
        }
        .logout-btn svg {
            width: 20px;
            height: 20px;
        }
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

        /* ===== VIEW TOGGLE ===== */

        .view-toggle {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.toggle-btn {
    padding: 0.4rem 1rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    background: white;
    cursor: pointer;
    font-weight: 600;
}

.toggle-btn.active {
    background: #b91c1c;
    color: white;
    border-color: #b91c1c;
}

/* FORCE HIDE CLASS */
.d-none {
    display: none !important;
}

/* ===== CARD VIEW ===== */

.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.5rem;
}

.course-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: 0.3s ease;
}

.course-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 35px rgba(185,28,28,0.25);
}

.course-card-image {
    height: 140px;
    background: linear-gradient(135deg, #dbeafe, #f1f5f9);
}

.course-card-body {
    padding: 1.25rem;
}

.course-card-body h3 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}

.course-card-body p {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.course-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-open {
    font-size: 0.85rem;
    font-weight: 600;
    color: #b91c1c;
    text-decoration: none;
}
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header"><img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo"></div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    <span>Courses</span>
                </a>
                <a href="{{ route('profile.show') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('enroll') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                    <span>Enroll Online</span>
                </a>
                <a href="{{ route('certificates.index') }}" class="nav-item">
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
            <h1 class="page-title">My Courses</h1>
            <p class="page-subtitle">School year {{ $schoolYear }}</p>
            <div class="view-toggle">
            <span>View as:</span>
            <button id="listViewBtn" class="toggle-btn active">List</button>
            <button id="cardViewBtn" class="toggle-btn">Cards</button>
        </div>
            @if($collegeCourses->isNotEmpty())
                <p class="college-course-header">Your program: {{ $collegeCourses->pluck('name')->join(', ') }}</p>
            @endif

            @if(session('success'))
                <p style="color: #16a34a; margin-bottom: 1rem;">{{ session('success') }}</p>
            @endif
    
            <div class="courses-wrapper">

    <!-- LIST VIEW -->
    <div id="listView" class="courses-card">
        @if($enrollments->isEmpty() && $allCourses->isEmpty())
            <div class="empty-state">
                <p>You are not enrolled in any courses for this school year.</p>
                <p style="margin-top: 0.5rem;">
                    <a href="{{ route('enroll') }}">Enroll online</a>
                </p>
            </div>
        @elseif($enrollments->isNotEmpty())
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>Courses</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th>Enrolled at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $e)
                    <tr>
                        <td>
                            <a href="{{ route('courses.show', $e->course_id) }}" style="color:#dc2626;">
                                {{ $e->course_name ?? '—' }}
                            </a>
                        </td>
                        <td>{{ $e->section_name ?? '—' }}</td>
                        <td>{{ ucfirst($e->status) }}</td>
                        <td>{{ $e->enrolled_at->format('M j, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Code</th>
                    </tr>
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
        @endif
    </div>

    <!-- CARD VIEW -->
    <div id="cardView" class="cards-container d-none">
        @if($enrollments->isNotEmpty())
            @foreach($enrollments as $e)
            <div class="course-card">
                <div class="course-card-image"></div>
                <div class="course-card-body">
                    <h3>{{ $e->course_name }}</h3>
                    <p>{{ $e->section_name }}</p>
                    <div class="course-card-footer">
                        <span>{{ ucfirst($e->status) }}</span>
                        <a href="{{ route('courses.show', $e->course_id) }}" class="card-open">Open</a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            @foreach($allCourses as $c)
            <div class="course-card">
                <div class="course-card-image">
                    @if($c->banner_path)<img src="{{ asset('storage/' . $c->banner_path) }}" alt="" style="width:100%;height:100%;object-fit:cover;">@endif
                </div>
                <div class="course-card-body">
                    <h3>{{ $c->title }}</h3>
                    <p>{{ $c->code ?? '—' }}</p>
                    <div class="course-card-footer">
                        <span>Assigned</span>
                        <a href="{{ route('courses.show', $c) }}" class="card-open">Open</a>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>

        </div>
    </div>
</body>
</html>

<script>
const listBtn = document.getElementById('listViewBtn');
const cardBtn = document.getElementById('cardViewBtn');
const listView = document.getElementById('listView');
const cardView = document.getElementById('cardView');

listBtn.addEventListener('click', () => {
    listView.classList.remove('d-none');
    cardView.classList.add('d-none');
    listBtn.classList.add('active');
    cardBtn.classList.remove('active');
});

cardBtn.addEventListener('click', () => {
    cardView.classList.remove('d-none');
    listView.classList.add('d-none');
    cardBtn.classList.add('active');
    listBtn.classList.remove('active');
    
});
</script>
