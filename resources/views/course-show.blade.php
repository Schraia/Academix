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

.dashboard-container{
    display:flex;
    min-height:100vh;
}

/* ===== SIDEBAR ===== */

.sidebar{
    width:250px;
    background:linear-gradient(180deg,#b91c1c,#7f1d1d);
    color:white;
    display:flex;
    flex-direction:column;
}

.sidebar-header{
    padding:2rem 1.5rem;
    text-align:center;
    border-bottom:1px solid rgba(255,255,255,.1);
}

.nav-menu{flex:1;padding:1rem 0}

.nav-item{
    display:flex;
    align-items:center;
    padding:1rem 1.5rem;
    text-decoration:none;
    color:white;
    font-weight:500;
    border-left:4px solid transparent;
    transition:.2s ease;
}

.nav-item:hover{
    background:rgba(255,255,255,.08);
}

.nav-item.active{
    background:rgba(255,255,255,.15);
    border-left:4px solid white;
}

.nav-logout{
    padding:1rem;
    border-top:1px solid rgba(255,255,255,.1);
}

.logout-btn{
    width:100%;
    padding:.75rem;
    border:none;
    border-radius:8px;
    background:rgba(255,255,255,.1);
    color:white;
    font-weight:600;
}

/* ===== MAIN ===== */

.main-content{
    flex:1;
    padding:2.5rem 3rem;
}

.back-link{
    display:inline-block;
    margin-bottom:1.25rem;
    font-weight:600;
    color:#b91c1c;
    text-decoration:none;
}
.back-link:hover{text-decoration:underline}

/* ===== HEADER ===== */

.course-header{
    margin-bottom:1rem;
}

.course-title{
    font-size:2rem;
    font-weight:700;
}

.course-code{
    color:#6b7280;
    font-size:.9rem;
}

/* ===== COURSE NAVIGATION ===== */

.course-nav{
    display:flex;
    gap:2rem;
    border-bottom:2px solid #cbd5e1;
    margin-bottom:1.5rem;
    padding-bottom:.75rem;
}

.course-nav a{
    text-decoration:none;
    font-weight:600;
    font-size:.95rem;
    color:#6b7280;
    position:relative;
    padding-bottom:.5rem;
    transition:.2s ease;
}

.course-nav a:hover{
    color:#b91c1c;
}

.course-nav a.active{
    color:#b91c1c;
}

.course-nav a.active::after{
    content:"";
    position:absolute;
    left:0;
    bottom:-10px;
    height:4px;
    width:100%;
    background:#b91c1c;
    border-radius:4px;
}

/* ===== BANNER ===== */

.course-banner,
.banner-placeholder{
    width:100%;
    height:200px;
    border-radius:16px;
    margin-bottom:2rem;
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

/* ===== GRID ===== */

.content-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:2.5rem;
}

@media(max-width:1000px){
    .content-grid{grid-template-columns:1fr}
}

/* ===== CARDS ===== */

.card{
    background:white;
    border-radius:16px;
    padding:1.5rem;
    margin-bottom:1.75rem;
    box-shadow:0 6px 20px rgba(0,0,0,.05);
}

.card h3{
    font-size:.85rem;
    font-weight:700;
    margin-bottom:1rem;
    text-transform:uppercase;
    letter-spacing:.5px;
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
.link-go:hover{text-decoration:underline}

.course-description{
    font-size:.95rem;
    line-height:1.7;
    color:#374151;
}
</style>
</head>

<body>
<div class="dashboard-container">

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo.png') }}" width="120">
    </div>

    <nav class="nav-menu">
        <a href="{{ route('dashboard') }}" class="nav-item">Dashboard</a>
        <a href="{{ route('courses.index') }}" class="nav-item active">Courses</a>
        <a href="{{ route('profile.show') }}" class="nav-item">Profile</a>
        <a href="{{ route('enroll') }}" class="nav-item">Enroll Online</a>
        <a href="{{ route('certificates.index') }}" class="nav-item">Certificates</a>
    </nav>

    <div class="nav-logout">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<!-- MAIN -->
<div class="main-content">

<a href="{{ route('courses.index') }}" class="back-link">← Back to Courses</a>

<div class="course-header">
    <div class="course-title">{{ $course->title }}</div>
    <div class="course-code">{{ $course->code ?? $course->id }}</div>
</div>

<div class="course-nav">
    <a href="{{ route('courses.lessons',$course) }}">Lessons</a>
    <a href="{{ route('courses.announcements',$course) }}">Announcements</a>
    <a href="{{ route('courses.grades',$course) }}">Grades</a>
</div>

@if($course->banner_path)
<img src="{{ asset('storage/'.$course->banner_path) }}" class="course-banner">
@else
<div class="banner-placeholder">
    {{ $course->title }} — Learning Materials
</div>
@endif

<div class="content-grid">

    <!-- LEFT COLUMN -->
    <div>

        <div class="card">
            <h3>About This Course</h3>
            <div class="course-description">
                {{ $course->description ?? 'No description available yet.' }}
            </div>
        </div>

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

    </div>

    <!-- RIGHT COLUMN -->
    <div>

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