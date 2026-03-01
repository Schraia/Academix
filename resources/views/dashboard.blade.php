<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')

<style>
  *{margin:0;padding:0;box-sizing:border-box;}

  :root{
    /* responsive spacing */
    --gap: clamp(.85rem, 1.1vw, 1.4rem);
    --card-pad-y: clamp(.75rem, 1.0vw, 1.15rem);
    --card-pad-x: clamp(.95rem, 1.2vw, 1.3rem);

    /* typography */
    --title-size: clamp(1.55rem, 2.2vw, 2.15rem);
    --subtitle-size: clamp(.88rem, 1.0vw, .98rem);

    /* card rounding/shadow */
    --radius: 18px;
    --shadow: 0 14px 30px rgba(17,24,39,0.08);

    /* right column width (shared by top header + dashboard grid) */
    --right-col: clamp(340px, 28vw, 520px);

    /* course card sizes (vh makes it adapt to screen height) */
    --course-thumb-h: clamp(64px, 8.2vh, 110px);
    --course-body-pad-y: clamp(.55rem, .75vh, .95rem);
    --course-body-pad-x: clamp(.75rem, 1.0vh, 1.1rem);

    /* limit list heights so page never grows */
    --ann-list-h: clamp(140px, 18vh, 240px);
  }

  html, body{
    height:100%;
    overflow:hidden;
  }

  body{
    font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen,Ubuntu,Cantarell,sans-serif;
    background:#f3f4f6;
    color:#111827;
  }

  .dashboard-container{
    display:flex;
    height:100vh;        
    overflow:hidden;     
  }


  .sidebar{
    width:280px;
    height:100vh;
    flex-shrink:0;
    background: linear-gradient(180deg, #cf1f1f 0%, #7f1d1d 100%);
    color:#fff;
    display:flex;
    flex-direction:column;
    box-shadow: 6px 0 30px rgba(0,0,0,0.18);
    position:relative;
    overflow:hidden;
  }
  .sidebar::before{
    content:'';
    position:absolute;
    inset:0;
    background:
      radial-gradient(circle at 20% 20%, rgba(255,255,255,0.14), transparent 45%),
      radial-gradient(circle at 80% 60%, rgba(255,255,255,0.10), transparent 50%);
    pointer-events:none;
  }

  .sidebar-header{
    padding: 1.35rem 1.25rem;
    border-bottom: 1px solid rgba(255,255,255,0.12);
    display:flex;
    align-items:center;
    gap:.9rem;
    position:relative;
    z-index:1;
  }
  .sidebar-logo{
    width:62px;height:62px;
    object-fit:contain;
    border-radius:999px;
    background: rgba(255,255,255,0.10);
    padding:8px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.18);
  }
  .brand-text{
    font-size:1.45rem;
    font-weight:900;
    letter-spacing:-0.02em;
    line-height:1;
  }

  .nav-menu{
    flex:1;
    min-height:0;
    overflow:auto; 
    padding: 1rem 0.75rem;
    position:relative;
    z-index:1;
  }
  .nav-menu::-webkit-scrollbar{width:8px;}
  .nav-menu::-webkit-scrollbar-thumb{background:rgba(255,255,255,.22);border-radius:10px;}

  .nav-item{
    padding: 0.95rem 1rem;
    cursor:pointer;
    transition: all .22s ease;
    display:flex;
    align-items:center;
    gap:0.85rem;
    font-weight:700;
    border-radius:14px;
    margin: .35rem 0;
    position:relative;
    color:inherit;
  }
  .nav-item svg{width:20px;height:20px;opacity:.95;}

  .nav-item:hover{
    background: rgba(255,255,255,0.10);
    transform: translateX(2px);
  }

  .nav-item.active{
    background: rgba(255,255,255,0.16);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.15);
  }
  .nav-item.active::before{
    content:"";
    position:absolute;
    left:-10px;
    top:50%;
    transform:translateY(-50%);
    width:6px;
    height:34px;
    border-radius:999px;
    background:#fff;
    opacity:.95;
  }

  .nav-logout{
    padding: 1rem 1rem 1.25rem;
    border-top: 1px solid rgba(255,255,255,0.10);
    position:relative;
    z-index:1;
  }
  .logout-btn{
    width:100%;
    padding: 0.95rem;
    background: rgba(255,255,255,0.10);
    color:white;
    border: 1px solid rgba(255,255,255,0.18);
    border-radius:16px;
    cursor:pointer;
    font-size:1rem;
    font-weight:900;
    transition: all .22s ease;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:.6rem;
  }
  .logout-btn svg{width:20px;height:20px;}
  .logout-btn:hover{
    background:white;
    color:#b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(0,0,0,0.15);
  }


  .main-content{
    flex:1;
    min-width:0;
    height:100vh;
    overflow:hidden; /* important: no page scroll */
    padding: clamp(1rem, 1.6vw, 1.8rem) clamp(1.1rem, 2vw, 2.2rem);
    background: linear-gradient(180deg, #f6f7fb 0%, #f3f4f6 100%);
    display:flex;
    flex-direction:column;
    gap: var(--gap);
  }


  .top-header{
    display:grid;
    grid-template-columns: 1fr var(--right-col);
    gap: var(--gap);
    align-items:start;
  }
  .top-header .title{
    font-size: var(--title-size);
    font-weight: 950;
    letter-spacing:-0.03em;
  }
  .top-header .subtitle{
    margin-top: .35rem;
    color:#6b7280;
    font-size: var(--subtitle-size);
    font-weight:500;
  }
  .top-header-left{ min-width:0; }

  .dashboard-grid{
    flex:1;                 /* take remaining height */
    min-height:0;           /* allow internal sizing */
    display:grid;
    grid-template-columns: 1fr var(--right-col);
    gap: var(--gap);
    align-items:start;
    overflow:hidden;        /* no page scroll */
  }

  .left-col, .right-col{
    min-width:0;
    min-height:0;
    display:flex;
    flex-direction:column;
    gap: var(--gap);
    overflow:hidden; 
  }


  .dash-card{
    background:#fff;
    border:1px solid rgba(229,231,235,0.95);
    border-radius:var(--radius);
    padding: var(--card-pad-y) var(--card-pad-x);
    box-shadow: var(--shadow);
  }
  .dash-card h3, .dash-card h2{
    font-size: clamp(.95rem, 1.0vw, 1rem);
    font-weight: 950;
    color:#111827;
    margin-bottom: .35rem;
  }
  .dash-card p{
    font-size: clamp(.84rem, .95vw, .9rem);
    color:#6b7280;
    margin-bottom: .65rem;
  }


  .top-profile{ width:100%; }

  .profile-container{
    display:flex;
    align-items:center;
    gap: .9rem;
  }
  .profile-container .avatar{
    width: clamp(54px, 5.4vh, 62px);
    height: clamp(54px, 5.4vh, 62px);
    border-radius:999px;
    background:#eef2ff;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#6b7280;
    font-size:1.25rem;
    flex-shrink:0;
    border:1px solid #e5e7eb;
  }
  .profile-container .avatar img{
    width:100%;height:100%;
    border-radius:999px;
    object-fit:cover;
  }
  .profile-container .info{flex:1;min-width:0;}
  .profile-container .name{font-size:1.02rem;font-weight:950;}
  .profile-container .meta{font-size:.84rem;color:#6b7280;}

  .profile-container .btn-profile{
    padding:.55rem 1.05rem;
    background:#dc2626;
    color:#fff;
    border-radius:14px;
    font-weight:950;
    font-size:.88rem;
    text-decoration:none;
    white-space:nowrap;
    box-shadow: 0 14px 26px rgba(220,38,38,0.18);
    transition:.2s ease;
  }
  .profile-container .btn-profile:hover{
    background:#b91c1c;
    transform:translateY(-1px);
  }


  .todays-schedule-date{
    font-size:.85rem;
    color:#6b7280;
    margin-bottom:.55rem;
  }
  .todays-schedule-list{
    list-style:none;
    border:1px solid #e5e7eb;
    border-radius:14px;
    background:#fff;
    overflow:hidden;
  }
  .todays-schedule-list li{
    padding:.62rem .85rem;
    border-bottom:1px solid #f3f4f6;
    font-size:.9rem;
    color:#374151;
  }
  .todays-schedule-list li:last-child{border-bottom:none;}
  .todays-schedule-list a{color:#dc2626;text-decoration:underline;}
  .todays-schedule-chevron{
    display:block;text-align:center;padding:.45rem;color:#9ca3af;font-size:.75rem;
  }

  .right-mini-card{
    display:flex;
    gap: .9rem;
    align-items:center;
  }
  .mini-icon{
    width: clamp(46px, 5.2vh, 52px);
    height: clamp(46px, 5.2vh, 52px);
    border-radius:16px;
    background:#ffe4e6;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#dc2626;
    flex-shrink:0;
  }
  .mini-icon svg{width:26px;height:26px;}

  .dash-card .btn-nav{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:.5rem .95rem;
    background:#dc2626;
    color:#fff;
    border-radius:14px;
    font-size:.86rem;
    font-weight:950;
    text-decoration:none;
    box-shadow: 0 14px 26px rgba(220,38,38,0.16);
    transition:.2s ease;
  }
  .dash-card .btn-nav:hover{background:#b91c1c;transform:translateY(-1px);}

  .course-overview-card{
    flex:1;           
    min-height:0;     
    display:flex;
    flex-direction:column;
    overflow:hidden;
  }
  .course-overview-card .head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom: .75rem;
    flex-shrink:0;
  }
  .course-overview-card .head .label{
    display:flex;
    align-items:center;
    gap:.6rem;
    font-weight:950;
  }
  .course-overview-card .head .label .dot{
    width:10px;height:10px;border-radius:3px;background:#dc2626;
  }
  .course-overview-card .head .view-all{
    color:#6b7280;
    font-weight:800;
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:.45rem;
  }
  .course-overview-card .head .view-all:hover{color:#dc2626;}

  .courses-wrapper{
    flex:1;
    min-height:0;
    display:flex;
    align-items:stretch;
    gap:.5rem;
    overflow:hidden;
  }
  .courses-scroll-viewport{
    flex:1;
    min-width:0;
    min-height:0;
    overflow-x:auto;
    overflow-y:hidden;
    padding-bottom:.2rem;
  }
  .courses-scroll-viewport::-webkit-scrollbar{height:8px;}
  .courses-scroll-viewport::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px;}

  .courses-scroll-track{
    display:flex;
    gap:1rem;
    padding:.25rem 0;
    height:100%;
  }

  /* 2x3 grid page */
  .courses-page{
    display:grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    grid-template-rows: repeat(2, minmax(0, 1fr));
    gap: clamp(.75rem, 1vw, 1rem);
    flex:0 0 auto;
    min-width:0;
  }

  .course-card-dash-wrap{display:block;text-decoration:none;color:inherit;height:100%;}
  .course-card-dash{
    height:100%;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    border:1px solid rgba(229,231,235,0.95);
    box-shadow: 0 10px 22px rgba(17,24,39,0.06);
    transition:.2s ease;
    display:flex;
    flex-direction:column;
  }
  .course-card-dash:hover{transform:translateY(-3px);box-shadow:0 18px 35px rgba(17,24,39,0.12);}

  .course-card-dash .thumb{
    height: var(--course-thumb-h);
    background: linear-gradient(135deg, #fecaca, #fff1f2);
    flex-shrink:0;
  }
  .course-card-dash .thumb img{width:100%;height:100%;object-fit:cover;}

  .course-card-dash .body{
    padding: var(--course-body-pad-y) var(--course-body-pad-x);
    display:flex;
    flex-direction:column;
    gap:.35rem;
    flex:1;
    min-height:0;
  }
  .course-card-dash .code{
    font-size: clamp(.9rem, .95vw, .95rem);
    font-weight:950;
  }
  .course-card-dash .title{
    font-size: clamp(.82rem, .9vw, .88rem);
    color:#6b7280;
    line-height:1.25;
    margin-bottom:.2rem;
  }

  .course-card-dash .icons{
    margin-top:auto;
    display:flex;
    gap:.6rem;
    align-items:center;
    opacity:.95;
  }
  .course-card-dash .icons span{
    color:#6b7280;
    display:inline-flex;
    cursor:pointer;
    transition:.2s;
  }
  .course-card-dash .icons span:hover{color:#dc2626;}
  .course-card-dash .icons svg{width:18px;height:18px;}

  .courses-overflow-btn{
    flex-shrink:0;
    width:44px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    color:#374151;
    font-size:1.25rem;
    font-weight:950;
    cursor:pointer;
    box-shadow: 0 10px 20px rgba(17,24,39,0.06);
    height: 100%;
    max-height: 340px; 
    align-self:center;
  }
  .courses-overflow-btn:hover{background:#f3f4f6;color:#dc2626;}

  .card-badges{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.35rem;}
  .card-badge{font-size:.72rem;font-weight:950;padding:.22rem .5rem;border-radius:8px;}
  .card-badge.grades{ background:#fef2f2; color:#dc2626; }
  .card-badge.announcements{ background:#eff6ff; color:#2563eb; }
  .card-badge.lessons{ background:#f0fdf4; color:#16a34a; }
  .card-badge.discussions{ background:#faf5ff; color:#7c3aed; }


  .announcements-wrap{
    max-height: var(--ann-list-h);
    overflow:auto; /* internal scroll only if many items */
  }
  .announcements-wrap::-webkit-scrollbar{width:6px;}
  .announcements-wrap::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px;}

  .announcements-list,.recently-list{list-style:none;}
  .announcements-list li,.recently-list li{
    padding:.5rem 0;
    border-bottom:1px solid #f3f4f6;
    font-size:.92rem;
  }
  .announcements-list li:last-child,.recently-list li:last-child{border-bottom:none;}
  .announcements-list a,.recently-list a{color:#dc2626;text-decoration:underline;}
  .recently-list .course-label{font-size:.78rem;color:#6b7280;margin-top:.15rem;}

  ========================= */
  .dash-card svg{max-width:100%;max-height:100%;}

  @media (max-width: 1100px){
    html, body{ overflow:auto; } /* allow normal scrolling on small screens */
    .dashboard-container{ height:auto; overflow:visible; }
    .main-content{ height:auto; overflow:visible; }
    .dashboard-grid{ grid-template-columns:1fr; overflow:visible; }
    .top-header{ grid-template-columns:1fr; }
  }
</style>
</head>

<body>
<div class="dashboard-container">

    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo">
            <div class="brand-text">Academix</div>
        </div>

        <nav class="nav-menu">
            <div class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span>Dashboard</span>
            </div>

            <a href="{{ route('courses.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('profile.show') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                </svg>
                <span>Profile</span>
            </a>

            <a href="{{ route('enroll') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <span>Enroll Online</span>
            </a>

            <a href="{{ route('certificates.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                </svg>
                <span>Certificates</span>
            </a>

            @if(Auth::user()->isAdmin())
            <a href="{{ route('settings.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
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

        <div class="top-header">
    <div class="top-header-left">
        <div class="title">Welcome Back, {{ Auth::user()->isAdmin() ? 'Admin' : Auth::user()->name }}!</div>
        <div class="subtitle">Here’s what’s happening today in your Academix dashboard.</div>
    </div>

    <!-- MOVE PROFILE CARD HERE (top right) -->

</div>

        <div class="dashboard-grid">

            <!-- LEFT: profile + schedule + course overview -->
            <div class="left-col">

                

                <div class="todays-schedule-section dash-card">
                    <h2 class="todays-schedule-header">Today's Schedule</h2>
                    <p class="todays-schedule-date">{{ $dateFormatted }}</p>

                    <ul class="todays-schedule-list">
                        @forelse($todaysSchedules as $schedule)
                            <li>{{ $schedule['time_slot'] }} - <a href="{{ route('courses.show', $schedule['course_id']) }}">{{ $schedule['course_code'] ? $schedule['course_code'] . ' - ' : '' }}{{ $schedule['display_title'] }}</a></li>
                        @empty
                            <li style="color:#9ca3af;">No classes scheduled for today.</li>
                        @endforelse
                    </ul>

                    @if(count($todaysSchedules) > 0)
                        <span class="todays-schedule-chevron" aria-hidden="true">▼</span>
                    @endif
                </div>

                <!-- COURSE OVERVIEW BIG CARD (like picture) -->
                <div class="dash-card course-overview-card">
                    <div class="head">
                        <div class="label"><span class="dot"></span> Course Overview</div>
                        <a class="view-all" href="{{ route('courses.index') }}">View All <span style="font-size:1.1rem;">›</span></a>
                    </div>

                    <div class="courses-wrapper">
                        <div class="courses-scroll-viewport" id="coursesScroll">
                            <div class="courses-scroll-track">

                                @if($dashboardCards->isEmpty())
                                    <div class="courses-page" style="display:block;width:100%;">
                                        <div class="dash-card" style="box-shadow:none;border:1px dashed #e5e7eb;padding:2rem;text-align:center;color:#6b7280;">
                                            @if(Auth::user()->isInstructor())
                                                <p>No courses assigned yet. Ask an admin to assign courses to you in Settings.</p>
                                            @elseif(Auth::user()->isAdmin())
                                                <p>No courses in the system yet, or assign yourself as instructor to see them here.</p>
                                            @else
                                                <p>No courses. <a href="{{ route('enroll') }}" style="color:#dc2626;">Enroll online</a> to get started.</p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    @foreach($dashboardCards->chunk(6) as $chunk)
                                    <div class="courses-page">
                                        @foreach($chunk as $item)
                                            @php
                                                $c = $item->course;
                                                $cid = $item->course_id;
                                                $badges = $cardBadges[$cid] ?? null;
                                            @endphp

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
                                                            @if($badges->grades > 0)
                                                                <span class="card-badge grades">{{ $badges->grades }} grade{{ $badges->grades !== 1 ? 's' : '' }}</span>
                                                            @endif
                                                            @if($badges->announcements > 0)
                                                                <span class="card-badge announcements">{{ $badges->announcements }}</span>
                                                            @endif
                                                            @if($badges->lessons > 0)
                                                                <span class="card-badge lessons">{{ $badges->lessons }}</span>
                                                            @endif
                                                            @if($badges->discussions > 0)
                                                                <span class="card-badge discussions">{{ $badges->discussions }}</span>
                                                            @endif
                                                        </div>
                                                        @endif

                                                        <div class="icons">
                                                            <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.announcements', $cid) }}';" title="Announcements">
                                                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                                                            </span>
                                                            <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.lessons', $cid) }}';" title="Lessons">
                                                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                                                            </span>
                                                            <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.grades', $cid) }}';" title="Grades">
                                                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                                            </span>
                                                            <span onclick="event.preventDefault(); event.stopPropagation(); window.location='{{ route('courses.discussions', $cid) }}';" title="Chat / Forum">
                                                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/></svg>
                                                            </span>
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
                            <button type="button" class="courses-overflow-btn" id="coursesScrollRight" aria-label="Scroll courses">›</button>
                        @endif
                    </div>
                </div>

            </div><!-- left-col -->

            <!-- RIGHT: top cards + announcements + recently -->
            <div class="right-col">
                    <div class="top-profile dash-card profile-container">
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
                <div class="dash-card">
                    <div class="right-mini-card">
                        <div class="mini-icon">
                            <!-- bar chart icon -->
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M5 9h3v10H5V9zm5-4h3v14h-3V5zm5 7h3v7h-3v-7z"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <h3 style="margin-bottom:.2rem;">Diagnostics</h3>
                            <p style="margin-bottom:.65rem;">Your learning stats &amp; progress</p>
                            <div class="mini-actions">
                                <a href="{{ route('profile.show') }}" class="btn-nav">View Diagnostics ›</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="right-mini-card">
                        <div class="mini-icon" style="background:#ffe9ec;">
                            <!-- megaphone icon -->
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 11v2l10 3v-8L3 11zm12-3v8l5 2V6l-5 2zM7 16v3h2v-2.4L7 16z"/></svg>
                        </div>
                        <div style="min-width:0;">
                            <h3 style="margin-bottom:.2rem;">Announcements</h3>
                            <p style="margin-bottom:0;">No recent announcements.</p>
                        </div>
                    </div>
                </div>

                <div class="dash-card">
                    <h3 class="section-title">Announcements</h3>
                    <div class="announcements-wrap">
                        <ul class="announcements-list">
                            @forelse($announcements as $a)
                            <li class="announcement-item">
                                <div style="font-size:.78rem;color:#9ca3af;margin-bottom:.15rem;">{{ $a->created_at->format('M j') }}</div>
                                <div style="font-size:.84rem;color:#6b7280;margin-bottom:.2rem;">
                                    {{ $a->course ? $a->course->title . ($a->course->code ? ' (' . $a->course->code . ')' : '') : '—' }}
                                </div>
                                <a href="{{ route('courses.announcements', $a->course_id) }}" style="font-weight:950;color:#111827;text-decoration:none;">
                                    {{ Str::limit($a->title, 50) }}
                                </a>
                                <div style="font-size:.78rem;color:#9ca3af;margin-top:.15rem;">{{ $a->created_at->format('g:i A - M j, Y') }}</div>
                            </li>
                            @empty
                            <li style="color:#9ca3af;">No announcements.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="dash-card">
                    <h3 class="section-title">Recently Opened</h3>
                    <ul class="recently-list">
                        @forelse($recentlyOpened as $lesson)
                        <li>
                            <a href="{{ route('courses.lessons.preview', [$lesson->course_id, $lesson]) }}">
                                {{ Str::limit($lesson->attachment_original_name ?? $lesson->title, 35) }}
                            </a>
                            @if($lesson->course)
                                <div class="course-label">From: {{ $lesson->course->title }}{{ $lesson->course->code ? ' (' . $lesson->course->code . ')' : '' }}</div>
                            @endif
                        </li>
                        @empty
                        <li style="color:#9ca3af;">No recent files.</li>
                        @endforelse
                    </ul>
                </div>

            </div><!-- right-col -->

        </div><!-- dashboard-grid -->
    </div><!-- main-content -->
</div><!-- dashboard-container -->

<script>
(function(){
    var viewport = document.getElementById('coursesScroll');
    var btn = document.getElementById('coursesScrollRight');
    var track = viewport ? viewport.querySelector('.courses-scroll-track') : null;
    var pages = track ? track.querySelectorAll('.courses-page') : [];

    function setPageWidths(){
        if (!viewport || !track || pages.length === 0) return;
        var w = viewport.clientWidth;
        for (var i = 0; i < pages.length; i++){
            pages[i].style.width = w + 'px';
            pages[i].style.minWidth = w + 'px';
        }
    }

    if (viewport && track && pages.length){
        function initPageWidths(){ setPageWidths(); }
        if (document.readyState === 'loading'){
            document.addEventListener('DOMContentLoaded', initPageWidths);
        } else {
            initPageWidths();
        }
        setTimeout(setPageWidths, 0);
        window.addEventListener('resize', setPageWidths);

        if (btn){
            btn.addEventListener('click', function(){
                var scrollLeft = viewport.scrollLeft;
                var scrollWidth = viewport.scrollWidth;
                var clientWidth = viewport.clientWidth;
                var gap = 16;
                var pageWidth = clientWidth + gap;
                var atEnd = scrollLeft + clientWidth >= scrollWidth - 2;
                if (atEnd && pages.length > 1){
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