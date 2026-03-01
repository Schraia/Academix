<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $course->title }} - Academix</title>
@vite('resources/css/app.css')

<style>
*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
    background:#f3f4f6;
    color:#111827;
}

/* ===== LAYOUT ===== */

.dashboard-container{
    display:flex;
    min-height:100vh;
}

/* ===== SIDEBAR ===== */

.sidebar { width: 260px; height: 100vh;position: sticky; top: 0;flex-shrink: 0;
        background:
        linear-gradient(180deg, #962121 0%, #991b1b 40%, #450a0a 100%);
        color: rgba(255,255,255,0.92);display: flex; flex-direction: column;box-shadow: 8px 0 40px rgba(0,0,0,0.35); overflow: hidden; }
        .sidebar::after { content: ""; position: absolute; inset: 0;
        background:
        radial-gradient(circle at 20% 10%, rgba(255,255,255,0.05), transparent 40%),
        radial-gradient(circle at 80% 30%, rgba(255,255,255,0.04), transparent 40%);
        pointer-events: none;
        }
        .sidebar::before { content: ''; position: absolute; top: 0; right: 0; width: 3px; height: 100%; background: linear-gradient(to bottom, rgba(255,255,255,0.5), transparent); opacity: 0.3; }
                .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; justify-content: center; align-items: center; }
                .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
                .sidebar-logo {
            max-width: 140px;
            filter: drop-shadow(0 6px 12px rgba(0,0,0,0.4));
        }
        .nav-menu { flex: 1; min-height: 0; overflow-y: auto; padding: 1rem 0; }
        .nav-item {
            padding: 0.9rem 1.75rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.25s ease;
        } 
        .nav-item:hover { background: rgba(255, 255, 255, 0.08); padding-left: 2.10rem; }
        .nav-item:hover svg { transform: scale(1.1); }
        .nav-item svg { width: 19px;
            height: 19px;
            opacity: 0.85;
            transition: all 0.25s ease;}
            .nav-item:hover svg {
            opacity: 1;
            transform: scale(1.15);
        }
                .nav-item.active {
            background: rgba(255,255,255,0.12);
        }

        .nav-item.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;

            background: linear-gradient(180deg, #ef4444, #ffffff);
            border-radius: 0 6px px 0;
        }
        .nav-logout {
            margin-top: auto;
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .logout-btn { width: 100%; padding: 0.75rem; background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .logout-btn:hover { background: white; color: #b91c1c; transform: translateY(-2px); }
        .logout-btn svg { width: 20px; height: 20px; }

/* ===== MAIN ===== */

.main-content {flex: 1; padding: 3rem 4rem; overflow-y: auto; background:
        radial-gradient(circle at 10% 10%, rgba(185,28,28,0.18), transparent 50%),
        radial-gradient(circle at 90% 30%, rgba(220,38,38,0.15), transparent 50%),
        linear-gradient(180deg, #ffffff 0%, #f3f4f6 100%);}

/* Header */

.back-link{
    display:inline-block;
    margin-bottom:1.25rem;
    font-weight:600;
    color:#b91c1c;
    text-decoration:none;
}

.course-title{
    font-size:2rem;
    font-weight:700;
}

.course-code{
    color:#6b7280;
    font-size:.9rem;
    margin-bottom:1.25rem;
}

/* ===== COURSE NAV ===== */

.course-nav{
    display:flex;
    gap:2.5rem;
    border-bottom:2px solid #e5e7eb;
    margin-bottom:2.5rem;
    padding-bottom:.85rem;
}

.course-nav a{
    text-decoration:none;
    font-weight:600;
    color:#6b7280;
    position:relative;
    transition:.2s ease;
}

.course-nav a:hover{
    color:#b91c1c;
}

/* ===== GRID ===== */

.top-section{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:3rem;
    align-items:start;
    position:relative;
}

/* subtle divider */
.top-section::after{
    content:"";
    position:absolute;
    top:0;
    bottom:0;
    left:66%;
    width:1px;
    background:#e5e7eb;
}

@media(max-width:1100px){
    .top-section{
        grid-template-columns:1fr;
    }
    .top-section::after{
        display:none;
    }
}

/* ===== LEFT COLUMN ===== */

.left-column{
    display:flex;
    flex-direction:column;
    gap:2rem;
}

/* ===== RIGHT COLUMN ===== */

.right-column{
    display:flex;
    flex-direction:column;
    gap:1.75rem;
}

/* ===== BANNER ===== */

.course-banner,
.banner-placeholder{
    width:100%;
    height:230px;
    border-radius:18px;
    object-fit:cover;
}

.banner-placeholder{
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(135deg,#1e3a5f,#3b82f6);
    color:white;
    font-weight:600;
}

/* ===== CARDS ===== */

.card{
    background:white;
    border-radius:18px;
    padding:1.75rem;
    box-shadow:0 8px 24px rgba(0,0,0,.05);
}

.card h3{
    font-size:.8rem;
    font-weight:700;
    margin-bottom:1rem;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:#6b7280;
}

.preview{
    font-size:.875rem;
    color:#374151;
    margin-bottom:.6rem;
}

.link-go{
    font-size:.85rem;
    font-weight:600;
    color:#b91c1c;
    text-decoration:none;
}

.link-go:hover{
    text-decoration:underline;
}

.course-description{
    font-size:.95rem;
    line-height:1.75;
    color:#374151;
}
</style>
</head>

<body>
<div class="dashboard-container">

<!-- SIDEBAR -->
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

<!-- MAIN -->
<div class="main-content">

<a href="{{ route('courses.index') }}" class="back-link">← Back to Courses</a>

<div class="course-title">{{ $course->title }}</div>
<div class="course-code">{{ $course->code ?? $course->id }}</div>

<div class="course-nav">
    <a href="{{ route('courses.lessons',$course) }}">Lessons</a>
    <a href="{{ route('courses.announcements',$course) }}">Announcements</a>
    <a href="{{ route('courses.grades',$course) }}">Grades</a>
</div>

<div class="top-section">

    <!-- LEFT -->
    <div class="left-column">

        @if($course->banner_path)
            <img src="{{ asset('storage/'.$course->banner_path) }}" class="course-banner">
        @else
            <div class="banner-placeholder">
                {{ $course->title }} — Learning Materials
            </div>
        @endif

        <div class="card">
            <h3>About This Course</h3>
            <div class="course-description">
                {{ $course->description ?? 'No description available yet.' }}
            </div>
        </div>

    </div>

    <!-- RIGHT -->
    <div class="right-column">

        <div class="card">
            <h3>Ongoing Discussions</h3>
            @forelse($ongoingThreads as $thread)
                <div class="preview">{{ Str::limit($thread->title,60) }}</div>
            @empty
                <div class="preview">No discussions yet.</div>
            @endforelse
            <a href="{{ route('courses.discussions',$course) }}" class="link-go">
                Open Discussions →
            </a>
        </div>

        <div class="card">
            <h3>Last Lesson</h3>
            @if($lastLesson)
                <div class="preview">{{ $lastLesson->title }}</div>
            @else
                <div class="preview">No lessons uploaded.</div>
            @endif
        </div>

        <div class="card">
            <h3>Recently Opened</h3>
            @forelse($recentLessons as $lesson)
                <div class="preview">{{ $lesson->title }}</div>
            @empty
                <div class="preview">No recent files.</div>
            @endforelse
        </div>

    </div>

</div>

</div>
</div>
</body>
</html>