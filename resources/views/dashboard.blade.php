<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f3f4f6;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            flex-shrink: 0;
            background: linear-gradient(180deg, #b91c1c 0%, #7f1d1d 100%);
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25);
            position: relative;
            overflow: hidden;
            overflow-x: hidden;
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
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            overflow-x: hidden;
            background: #f9fafb;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem 2rem;
            align-items: stretch;
            min-width: 0;
        }
        @media (max-width: 1100px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        .left-col {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 0;
        }
        .left-col .dash-card {
            flex-shrink: 0;
        }
        .dash-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .profile-container {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        .profile-container .avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .profile-container .avatar img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; }
        .profile-container .info {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .profile-container .name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
        }
        .profile-container .meta {
            font-size: 0.8125rem;
            color: #6b7280;
            line-height: 1.4;
            word-break: break-word;
        }
        .course-card-dash-wrap {
            display: block;
            text-decoration: none;
            color: inherit;
            height: 100%;
        }
        .course-card-dash-wrap:hover .course-card-dash { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .card-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            margin-top: 0.5rem;
        }
        .card-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.45rem;
            border-radius: 6px;
            background: #fef2f2;
            color: #dc2626;
        }
        .card-badge.grades { background: #fef2f2; color: #dc2626; }
        .card-badge.announcements { background: #eff6ff; color: #2563eb; }
        .card-badge.lessons { background: #f0fdf4; color: #16a34a; }
        .card-badge.discussions { background: #faf5ff; color: #7c3aed; }
        .announcement-item { margin-bottom: 0.75rem; }
        .announcement-item .ann-date { font-size: 0.75rem; color: #9ca3af; margin-bottom: 0.15rem; }
        .announcement-item .ann-course { font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.2rem; }
        .announcement-item .ann-title { font-size: 0.875rem; font-weight: 600; color: #1f2937; }
        .announcement-item .ann-time { font-size: 0.75rem; color: #9ca3af; margin-top: 0.15rem; }
        .recently-list .course-label { font-size: 0.75rem; color: #6b7280; margin-top: 0.15rem; }
        .courses-page { align-items: start; }
        .profile-container .btn-profile {
            padding: 0.5rem 1rem;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .profile-container .btn-profile:hover { background: #b91c1c; color: white; }
        .todays-schedule-section { margin-bottom: 0; }
        .todays-schedule-header { font-size: 1rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem; }
        .todays-schedule-date { font-size: 0.8125rem; color: #6b7280; margin-bottom: 0.5rem; }
        .todays-schedule-list {
            list-style: none;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }
        .todays-schedule-list li { padding: 0.5rem 0.75rem; border-bottom: 1px solid #e5e7eb; font-size: 0.875rem; color: #374151; }
        .todays-schedule-list li:last-child { border-bottom: none; }
        .todays-schedule-list a { color: #dc2626; text-decoration: underline; }
        .todays-schedule-chevron { display: block; text-align: center; padding: 0.35rem; color: #9ca3af; font-size: 0.75rem; }
        .check-grades-card, .diagnostics-card {
            position: relative;
        }
        .diagnostics-card .icon-wrap {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.4;
        }
        .check-grades-card .icon-wrap { display: none; }
        .diagnostics-card .icon-wrap { opacity: 0.4; }
        .dash-card h3 { font-size: 1rem; font-weight: 700; color: #1f2937; margin-bottom: 0.35rem; }
        .dash-card p { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.75rem; }
        .dash-card .btn-nav {
            display: inline-block;
            padding: 0.4rem 0.9rem;
            background: #dc2626;
            color: white;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
        }
        .dash-card .btn-nav:hover { background: #b91c1c; color: white; }
        .right-col {
            grid-column: 2;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        @media (max-width: 1100px) { .right-col { grid-column: 1; } }
        .courses-wrapper {
            display: flex;
            align-items: stretch;
            gap: 0.5rem;
            position: relative;
            min-width: 0;
        }
        .courses-scroll-viewport {
            flex: 1;
            min-width: 0;
            overflow-x: auto;
            overflow-y: hidden;
        }
        .courses-scroll-track {
            display: flex;
            gap: 1rem;
            padding: 0.25rem 0;
            height: 100%;
        }
        .courses-page {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(2, auto);
            gap: 1rem;
            flex: 0 0 auto;
            min-width: 0;
        }
        .courses-scroll-viewport::-webkit-scrollbar { height: 8px; }
        .courses-scroll-viewport::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
        .course-card-dash {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .course-card-dash:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .course-card-dash .thumb {
            height: 120px;
            background: linear-gradient(135deg, #fecaca, #fef2f2);
        }
        .course-card-dash .thumb img { width: 100%; height: 100%; object-fit: cover; }
        .course-card-dash .body { padding: 1rem 1.25rem; }
        .course-card-dash .code { font-size: 0.9375rem; font-weight: 700; color: #1f2937; }
        .course-card-dash .title { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem; line-height: 1.35; }
        .course-card-dash .icons {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }
        .course-card-dash .icons a, .course-card-dash .icons span {
            color: #6b7280;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .course-card-dash .icons a:hover, .course-card-dash .icons span:hover { color: #dc2626; }
        .course-card-dash .icons svg { width: 20px; height: 20px; }
        .courses-overflow-btn {
            flex-shrink: 0;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            font-size: 1.25rem;
            font-weight: 700;
            cursor: pointer;
            margin-left: 0.5rem;
        }
        .courses-overflow-btn:hover { background: #e5e7eb; color: #dc2626; }
        .section-title { font-size: 1rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem; }
        .announcements-list, .recently-list { list-style: none; }
        .announcements-wrap {
            max-height: 140px;
            overflow-y: auto;
        }
        .announcements-wrap::-webkit-scrollbar { width: 6px; }
        .announcements-wrap::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }
        .announcements-list li, .recently-list li {
            padding: 0.35rem 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
        }
        .announcements-list li:last-child, .recently-list li:last-child { border-bottom: none; }
        .announcements-list a, .recently-list a { color: #dc2626; text-decoration: underline; }
        .announcements-list .date, .recently-list .date { color: #6b7280; font-size: 0.8125rem; margin-right: 0.5rem; }
        .bottom-two { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo">
            </div>
            <nav class="nav-menu">
                <div class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span>Dashboard</span>
                </div>
                <a href="{{ route('courses.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Courses</span>
                </a>
                <a href="{{ route('profile.show') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                    </svg>
                    <span>Profile</span>
                </a>
                <a href="{{ route('enroll') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
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
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
        <div class="main-content">
            <div class="dashboard-grid">
                <div class="left-col">
                    <div class="dash-card profile-container">
                        <div class="avatar">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="">
                            @else
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="info">
                            <div class="name">{{ Auth::user()->name }}</div>
                            <div class="meta">{{ $profileRoleLabel }}</div>
                        </div>
                        <a href="{{ route('profile.show') }}" class="btn-profile">Profile</a>
                    </div>
                    <div class="todays-schedule-section dash-card">
                        <h2 class="todays-schedule-header">Today's Schedule:</h2>
                        <p class="todays-schedule-date">{{ $dateFormatted }}</p>
                        <ul class="todays-schedule-list">
                            @forelse($todaysSchedules as $schedule)
                            <li>{{ $schedule['time_slot'] }} - <a href="{{ route('courses.show', $schedule['course_id']) }}">{{ $schedule['course_code'] ? $schedule['course_code'] . ' - ' : '' }}{{ $schedule['display_title'] }}</a></li>
                            @empty
                            <li style="color: #9ca3af;">No classes scheduled for today.</li>
                            @endforelse
                        </ul>
                        @if(count($todaysSchedules) > 0)
                        <span class="todays-schedule-chevron" aria-hidden="true">▼</span>
                        @endif
                    </div>
                    <div class="dash-card check-grades-card">
                        <span class="icon-wrap" aria-hidden="true"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></span>
                        <h3>Check Grades</h3>
                        <p>Grades submitted by your instructor can be seen here.</p>
                        <a href="{{ route('profile.show') }}#grades-section" class="btn-nav">Navigate</a>
                    </div>
                    <div class="dash-card diagnostics-card">
                        <span class="icon-wrap" aria-hidden="true"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm3.707 9.707a1 1 0 10-1.414-1.414L10 11.586 8.707 12.293a1 1 0 00-1.414 0 1 1 0 000 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></span>
                        <h3>Diagnostics</h3>
                        <p>Your statistics about your learning curve.</p>
                        <a href="{{ route('profile.show') }}" class="btn-nav">Learn More</a>
                    </div>
                </div>
                <div class="right-col">
                    <div class="courses-wrapper">
                        <div class="courses-scroll-viewport" id="coursesScroll">
                            <div class="courses-scroll-track">
                                @if($dashboardCards->isEmpty())
                                <div class="courses-page" style="display: block; width: 100%;">
                                    <div class="dash-card" style="padding: 2rem; text-align: center; color: #6b7280;">
                                        @if(Auth::user()->isInstructor())
                                        <p>No courses assigned yet. Ask an admin to assign courses to you in Settings.</p>
                                        @elseif(Auth::user()->isAdmin())
                                        <p>No courses in the system yet, or assign yourself as instructor to see them here.</p>
                                        @else
                                        <p>No courses. <a href="{{ route('enroll') }}" style="color: #dc2626;">Enroll online</a> to get started.</p>
                                        @endif
                                    </div>
                                </div>
                                @else
                                @foreach($dashboardCards->chunk(6) as $chunk)
                                <div class="courses-page">
                                    @foreach($chunk as $item)
                                    @php $c = $item->course; $cid = $item->course_id; $badges = $cardBadges[$cid] ?? null; @endphp
                                    <a href="{{ route('courses.show', $c) }}" class="course-card-dash-wrap">
                                    <div class="course-card-dash">
                                        <div class="thumb">
                                            @if($c && $c->banner_path)
                                                <img src="{{ asset('storage/' . $c->banner_path) }}" alt="">
                                            @endif
                                        </div>
                                        <div class="body">
                                            <div class="code">{{ $c->code ?? '—' }}</div>
                                            <div class="title">{{ $c ? Str::limit($c->title, 28) : '—' }}</div>
                                            @if($badges && ($badges->grades > 0 || $badges->announcements > 0 || $badges->lessons > 0 || $badges->discussions > 0))
                                            <div class="card-badges">
                                                @if($badges->grades > 0)<span class="card-badge grades" title="New grades">{{ $badges->grades }} grade{{ $badges->grades !== 1 ? 's' : '' }}</span>@endif
                                                @if($badges->announcements > 0)<span class="card-badge announcements" title="New announcements">{{ $badges->announcements }}</span>@endif
                                                @if($badges->lessons > 0)<span class="card-badge lessons" title="New lessons">{{ $badges->lessons }}</span>@endif
                                                @if($badges->discussions > 0)<span class="card-badge discussions" title="New forum">{{ $badges->discussions }}</span>@endif
                                            </div>
                                            @endif
                                            <div class="icons">
                                                <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.announcements', $cid) }}';" title="Announcements"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg></span>
                                                <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.lessons', $cid) }}';" title="Lessons"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg></span>
                                                <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.grades', $cid) }}';" title="Grades"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></span>
                                                <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.discussions', $cid) }}';" title="Chat / Forum"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/></svg></span>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                    @endforeach
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @if($dashboardCards->count() > 6)
                        <button type="button" class="courses-overflow-btn" id="coursesScrollRight" aria-label="Scroll courses">&gt;</button>
                        @endif
                    </div>
                    <div class="bottom-two">
                        <div class="dash-card">
                            <h3 class="section-title">Announcements</h3>
                            <div class="announcements-wrap">
                                <ul class="announcements-list">
                                    @forelse($announcements as $a)
                                    <li class="announcement-item">
                                        <div class="ann-date">{{ $a->created_at->format('M j') }}</div>
                                        <div class="ann-course">{{ $a->course ? $a->course->title . ($a->course->code ? ' (' . $a->course->code . ')' : '') : '—' }}</div>
                                        <a href="{{ route('courses.announcements', $a->course_id) }}" class="ann-title">{{ Str::limit($a->title, 50) }}</a>
                                        <div class="ann-time">{{ $a->created_at->format('g:i A - M j, Y') }}</div>
                                    </li>
                                    @empty
                                    <li style="color: #9ca3af;">No announcements.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="dash-card">
                            <h3 class="section-title">Recently Opened:</h3>
                            <ul class="recently-list">
                                @forelse($recentlyOpened as $lesson)
                                <li>
                                    <a href="{{ route('courses.lessons.preview', [$lesson->course_id, $lesson]) }}">{{ Str::limit($lesson->attachment_original_name ?? $lesson->title, 35) }}</a>
                                    @if($lesson->course)<div class="course-label">From: {{ $lesson->course->title }}{{ $lesson->course->code ? ' (' . $lesson->course->code . ')' : '' }}</div>@endif
                                </li>
                                @empty
                                <li style="color: #9ca3af;">No recent files.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    (function(){
        var viewport = document.getElementById('coursesScroll');
        var btn = document.getElementById('coursesScrollRight');
        var track = viewport ? viewport.querySelector('.courses-scroll-track') : null;
        var pages = track ? track.querySelectorAll('.courses-page') : [];

        function setPageWidths() {
            if (!viewport || !track || pages.length === 0) return;
            var w = viewport.clientWidth;
            for (var i = 0; i < pages.length; i++) {
                pages[i].style.width = w + 'px';
                pages[i].style.minWidth = w + 'px';
            }
        }

        if (viewport && track && pages.length) {
            function initPageWidths() {
                setPageWidths();
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPageWidths);
            } else {
                initPageWidths();
            }
            setTimeout(setPageWidths, 0);
            window.addEventListener('resize', setPageWidths);

            if (btn) {
                btn.addEventListener('click', function() {
                    var scrollLeft = viewport.scrollLeft;
                    var scrollWidth = viewport.scrollWidth;
                    var clientWidth = viewport.clientWidth;
                    var gap = 16;
                    var pageWidth = clientWidth + gap;
                    var atEnd = scrollLeft + clientWidth >= scrollWidth - 2;
                    if (atEnd && pages.length > 1) {
                        viewport.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        viewport.scrollBy({ left: pageWidth, behavior: 'smooth' });
                    }
                });
            }

        }
    })();
    </script>
</body>
</html>

