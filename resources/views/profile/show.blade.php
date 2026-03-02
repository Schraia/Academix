
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%); }
        .dashboard-container { display: flex; min-height: 100vh; }
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
        .main-content {flex: 1; padding: 3rem 4rem; overflow-y: auto; background:
        radial-gradient(circle at 10% 10%, rgba(185,28,28,0.18), transparent 50%),
        radial-gradient(circle at 90% 30%, rgba(220,38,38,0.15), transparent 50%),
        linear-gradient(180deg, #ffffff 0%, #f3f4f6 100%);}
        .page-header {
            background: white;
            padding: 1.75rem 2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .page-title {
            font-size: 2.4rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    color: #0f172a;
}

        .page-subtitle {
            color: #6b7280;
            font-weight: 500;
            margin-top: 0.8rem;
            margin-bottom: 1rem;
        }
.page-header-left {
    display: flex;
    flex-direction: column;
}


        .btn-edit-profile {
    padding: 0.6rem 1.2rem;
    border-radius: 999px;
    border: 1px solid #dc2626;
    background: white;
    color: #dc2626;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.25s ease;
}
    
.btn-edit-profile:hover {
    background: #dc2626;
    color: white;
    box-shadow: 0 6px 16px rgba(220,38,38,0.25);
    transform: translateY(-2px);
}
        .alert-success { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
       .card {
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 22px;
    padding: 2rem;

    border: 1px solid #e2e8f0;

    box-shadow:
        0 8px 20px rgba(15,23,42,0.04),
        0 25px 50px rgba(15,23,42,0.05);

    transition: all 0.3s ease;
    margin-bottom: 1.5rem;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow:
        0 20px 45px rgba(15,23,42,0.08);
}

       .card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

       .profile-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1rem;
    align-items: start;
    margin-bottom: 2.5rem;  
}
        @media (max-width: 900px) { .profile-grid { grid-template-columns: 1fr; } }
        .profile-col-left { width: 100%; min-width: 0; }
        .profile-col-center { width: 100%; min-width: 0; }
        .profile-col-right { width: 100%; min-width: 0; }
        .profile-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.05);
    padding: 2rem 1.75rem;
    border: 1px solid #f1f5f9;
    transition: all 0.25s ease;
}
.profile-col-left {
    align-self: start;
}

.profile-card {
    min-height: 440px;
    display: flex;
    flex-direction: column;

    background: linear-gradient(145deg, #ffffff 0%, #f1f5f9 100%);
    border-radius: 22px;
    padding: 2.4rem 2rem;

    border: 1px solid rgba(255,255,255,0.6);

    box-shadow:
        0 10px 25px rgba(15, 23, 42, 0.05),
        0 30px 60px rgba(15, 23, 42, 0.06);

    backdrop-filter: blur(6px);

    transition: all 0.35s ease;
}
.profile-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 20px 40px rgba(15,23,42,0.08),
        0 40px 80px rgba(15,23,42,0.08);
}
       .profile-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #f1f5f9;
    margin: 0 auto 1rem;
    display: block;
}
      .profile-avatar-placeholder {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.25rem;
    font-weight: 600;
    color: #6b7280;
    border: 4px solid #f1f5f9;
    margin: 0 auto 1rem;
}
.profile-avatar,
.profile-avatar-placeholder {
    box-shadow:
        0 0 0 4px #ffffff,
        0 0 0 6px #fee2e2,
        0 10px 25px rgba(220,38,38,0.25);
}
        .profile-name { font-size: 1rem; font-weight: 600; color: #1f2937; text-align: center; margin-bottom: 0.25rem; }
        .profile-email { font-size: 0.8125rem; color: #6b7280; text-align: center; margin-bottom: 0.75rem; word-break: break-all; }
        .profile-bio-label,
        .profile-notes-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #94a3b8;
        }
        .profile-bio-text,
.profile-notes-text {
    font-size: 0.9rem;
    color: #374151;
    line-height: 1.6;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;

    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}
.profile-bio-text {
    font-size: 0.9rem;
    color: #374151;
    line-height: 1.6;

    display: -webkit-box;
    -webkit-line-clamp: 3;       
    -webkit-box-orient: vertical;

    overflow: hidden;
    text-overflow: ellipsis;
    word-break: break-word;
}
.profile-bio-view,
.profile-notes-view {
    margin-top: 0.5rem;
    padding-top: 0.5 rem;
    border-top: 1px solid #e2e8f0;
}

        .profile-bio-text.empty, .profile-notes-text.empty { color: #9ca3af; font-style: italic; }
        .profile-avatar-actions { margin-top: 0.5rem; display: none; }
        .profile-edit-mode .profile-avatar-actions { display: block; }
        .profile-edit-mode .profile-bio-view, .profile-edit-mode .profile-notes-view { display: none; }
        .profile-view-mode .profile-bio-edit, .profile-view-mode .profile-notes-edit { display: none; }
        .form-group { margin-bottom: 0.75rem; }
        .form-group label { display: block; font-weight: 500; color: #374151; margin-bottom: 0.25rem; font-size: 0.8125rem; }
        .form-group textarea { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; font-family: inherit; resize: vertical; min-height: 70px; }
        .form-group textarea:focus { outline: none; border-color: #dc2626; }
        .private-note-hint { font-size: 0.7rem; color: #6b7280; margin-top: 0.2rem; }
        .btn { padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 500; cursor: pointer; border: none; }
        .btn-primary {
    background: #b91c1c;
    color: white;
    border-radius: 10px;
    padding: 0.6rem 1.1rem;
    font-weight: 600;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: #991b1b;
    transform: translateY(-1px);
}
        .btn-danger { background: #fef2f2; color: #b91c1c; font-size: 0.8125rem; padding: 0.35rem 0.75rem; }
        .btn-danger:hover { background: #fee2e2; }
        .profile-avatar-actions input[type="file"] { font-size: 0.75rem; margin-bottom: 0.25rem; }
        .progress-bar-wrap { height: 12px; background: #e5e7eb; border-radius: 9999px; height: 10px; overflow: hidden; margin-top: 0.5rem; }
        .progress-bar-fill { height: 100%; background: linear-gradient(
        90deg,
        #b91c1c 0%,
        #dc2626 50%,
        #ef4444 100%
    );
    box-shadow: 0 4px 12px rgba(220,38,38,0.3);; border-radius: 9999px; transition: width 0.3s; }
        .progress-text { font-size: 0.9375rem; color: #6b7280; }
        .grades-table { width: 100%; border-collapse: collapse; }
        .grades-table th, .grades-table td { padding: 0.75rem 0; text-align: left; border-bottom: 1px solid #f3f4f6; }
        .grades-table th {
    font-weight: 600;
    color: #9ca3af;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
        .grade-value { font-weight: 600; color: #1f2937; }
        .discussion-list {
    max-height: 110px;      
    overflow-y: auto;
    padding-right: 4px;
}
.discussion-list::-webkit-scrollbar {
    width: 6px;
}

.discussion-list::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 6px;
}
        .discussion-list li { padding: 0.3rem 0; border-bottom: 1px solid #f3f4f6; }
        .discussion-list li:last-child { border-bottom: none; }
        .discussion-title-row { display: flex; align-items: center; gap: 0.35rem; flex-wrap: wrap; }
        .discussion-list a { color: #dc2626; text-decoration: none; font-weight: 500; font-size: 0.9rem; }
        .discussion-list a:hover { text-decoration: underline; }
        .btn-unfollow { background: none; border: none; cursor: pointer; padding: 0.2rem; color: #9ca3af; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; }
        .btn-unfollow:hover { color: #dc2626; background: #fef2f2; }
        .btn-unfollow svg { width: 16px; height: 16px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; z-index: 50; opacity: 0; visibility: hidden; transition: opacity 0.2s, visibility 0.2s; }
        .modal-overlay.open { opacity: 1; visibility: visible; }
        .modal-box { background: white; border-radius: 12px; padding: 1.5rem; max-width: 360px; width: 90%; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .modal-box h4 { font-size: 1.1rem; color: #1f2937; margin-bottom: 0.75rem; }
        .modal-actions { display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem; }
        .discussion-meta { font-size: 0.75rem; color: #6b7280; margin-top: 0.2rem; }
        .discussion-last-reply { font-size: 0.8125rem; color: #4b5563; margin-top: 0.35rem; margin-left: 1.25rem; padding-left: 0.75rem; border-left: 2px solid #e5e7eb; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .diagnostics-chart { display: flex; align-items: flex-end; gap: 4px; height: 120px; margin-top: 1rem; }
        .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; position: relative; cursor: default; }
        .chart-bar { width: 100%; max-width: 24px; background: #dc2626; border-radius: 4px 4px 0 0; min-height: 4px; transition: height 0.2s; }
        .chart-label { font-size: 0.7rem; color: #6b7280; margin-top: 0.35rem; text-align: center; }
        .chart-tooltip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); margin-bottom: 6px; padding: 0.4rem 0.6rem; background: #1f2937; color: #fff; font-size: 0.75rem; font-weight: 500; white-space: nowrap; border-radius: 6px; pointer-events: none; opacity: 0; visibility: hidden; transition: opacity 0.15s, visibility 0.15s; z-index: 5; }
        .chart-bar-wrap:hover .chart-tooltip { opacity: 1; visibility: visible; }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.2rem;

            border-radius: 999px;

            font-weight: 600;
            font-size: 0.85rem;

            border: 1px solid #dc2626;
            color: #dc2626;
            background: white;

            transition: all 0.25s ease;
        }
        .btn-outline:hover {
            background: #dc2626;
            color: white;
            box-shadow: 0 6px 16px rgba(220,38,38,0.25);
            transform: translateY(-2px);
        }
        .streak-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: #fef3c7; color: #92400e; padding: 0.5rem 1rem; border-radius: 9999px; font-weight: 600; font-size: 0.9375rem; margin-top: 0.75rem; }
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
                <div class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                    <span>Profile</span>
                </div>
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
            <div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your personal information and activity</p>
    </div>

    <button type="button" 
            class="btn-edit-profile" 
            id="profile-edit-toggle" 
            title="Edit profile"
            aria-label="Edit profile">
        Edit Profile
    </button>
</div>
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div style="background: #fef2f2; color: #b91c1c; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="profile-grid" id="profile-content">

                <div class="profile-col-left">
                    <div class="profile-card profile-view-mode" id="profile-left-card">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-avatar" id="profile-avatar-img">
                        @else
                            <div class="profile-avatar-placeholder" id="profile-avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                        @endif
                        <div class="profile-avatar-actions">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 0.5rem;">
                                @csrf
                                <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" id="profile_picture_input">
                                <button type="submit" class="btn btn-primary">Upload photo</button>
                            </form>
                            @if($user->profile_picture)
                            <form action="{{ route('profile.picture.remove') }}" method="POST" id="remove-photo-form">
                                @csrf
                                <button type="button" class="btn btn-danger" id="remove-photo-btn">Remove photo</button>
                            </form>
                            @endif
                        </div>
                        <p class="profile-name">{{ $user->name }}</p>
                        <p class="profile-email">{{ $user->email }}</p>
                        <div class="profile-bio-view">
                            <div class="profile-bio-label">About me</div>
                            <p class="profile-bio-text {{ empty($user->bio) ? 'empty' : '' }}">{{ $user->bio ?: 'No description added.' }}</p>
                        </div>
                        <div class="profile-notes-view" style="margin-top: 0.75rem;">
                            <div class="profile-notes-label">Private notes</div>
                            <p class="profile-notes-text {{ empty($user->private_notes) ? 'empty' : '' }}">{{ $user->private_notes ?: 'No private notes.' }}</p>
                        </div>
                        <form action="{{ route('profile.update') }}" method="POST" id="profile-save-form" style="display: none;">
                            @csrf
                            <div class="profile-bio-edit form-group">
                                <label for="bio">Description (visible on profile)</label>
                                <textarea id="bio" name="bio" rows="3" placeholder="Tell others a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                            <div class="profile-notes-edit form-group" style="margin-top: 0.75rem;">
                                <label for="private_notes">Private notes (only you see this)</label>
                                <textarea id="private_notes" name="private_notes" rows="3" placeholder="Notes only you can see...">{{ old('private_notes', $user->private_notes) }}</textarea>
                                <p class="private-note-hint">Only you can see this.</p>
                            </div>
                            <button type="submit" class="btn btn-primary">Save profile</button>
                        </form>
                    </div>
                </div>

                <div class="profile-col-center">
                    <div class="card"  id="grades-section">
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                            <h3 style="margin-bottom: 0;">📊 Grades Across Courses</h3>
                            <a href="{{ route('profile.enrollments') }}" class="btn-outline" style="margin-top: 0;">View all by year & semester →</a>
                        </div>
                        <p class="progress-text" style="margin-bottom: 0.75rem;">Current enrollment only.</p>
                        @if(!empty($gradesByCourse))
                            <table class="grades-table">
                                <thead>
                                    <tr><th>Course</th><th>Weighted grade</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($gradesByCourse as $item)
                                        <tr>
                                            <td>{{ $item['course']->title }}</td>
                                            <td class="grade-value">{{ $item['summary']['weighted_grade'] }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($overallGradeAverage !== null)
                                <p style="margin-top: 1rem; font-weight: 600; color: #1f2937;">Overall average (across courses): {{ $overallGradeAverage }}%</p>
                            @endif
                        @else
                            <p class="progress-text">No grades yet. Grades from your current enrolled courses will appear here.</p>
                        @endif
                    </div>
                   
                    <div class="card" style="margin-bottom: 0;">
                        <h3>💬 Discussion Activity</h3>
                        @if($discussionThreads->isNotEmpty())
                            <ul class="discussion-list">
                                @foreach($discussionThreads as $thread)
                                    <li>
                                        <div class="discussion-title-row">
                                            <a href="{{ route('courses.discussions.thread', [$thread->course_id, $thread]) }}">{{ $thread->title }}</a>
                                            <form action="{{ route('profile.discussions.unfollow', $thread) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-unfollow" title="Unfollow Discussion" aria-label="Unfollow Discussion">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="discussion-meta">{{ $thread->course?->title ?? 'Course' }} · {{ $thread->last_activity_at?->diffForHumans() }}</div>
                                        @php $lastMsg = $thread->messages->first(); @endphp
                                        @if($lastMsg)
                                            <div class="discussion-last-reply" title="{{ Str::limit(strip_tags($lastMsg->content ?? ''), 200) }}">{{ $lastMsg->user->name ?? 'Someone' }}: {{ Str::limit(strip_tags($lastMsg->content ?? ''), 80) }}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="progress-text">You haven’t participated in any discussions yet.</p>
                        @endif
                    </div>
                </div>
            </div>
       

                

            <div class="card">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem;">
                    <h3 style="margin-bottom: 0;">📈 Overall Progress</h3>
                    <a href="{{ route('profile.progress') }}" class="btn-outline" style="margin-top: 0;">View breakdown by course →</a>
                </div>
                <p class="progress-text">{{ $completedLessons }} of {{ $totalLessons }} lessons completed across all your courses</p>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width: {{ min($progressPercent, 100) }}%;"></div>
                </div>
                <p style="margin-top: 0.5rem; font-weight: 600; color: #1f2937;">{{ $progressPercent }}%</p>
            </div>

            <div class="card" id="diagnostics">
                <h3>🧠Learning Diagnostics</h3>
                <p class="progress-text">Lesson completions per week</p>
                <div class="diagnostics-chart">
                    @foreach($completionsByWeek as $week)
                        @php $max = max(1, collect($completionsByWeek)->max('count')); $h = $max > 0 ? (100 * $week['count'] / $max) : 0; @endphp
                        <div class="chart-bar-wrap">
                            <span class="chart-tooltip">{{ $week['label'] }}: {{ $week['count'] }} {{ $week['count'] === 1 ? 'module' : 'modules' }} completed</span>
                            <div class="chart-bar" style="height: {{ $h }}%;"></div>
                            <span class="chart-label">{{ $week['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <p style="margin-top: 0.5rem;" class="progress-text">Current streak: <strong>{{ $streak }} day(s)</strong> in a row with at least one lesson completed.</p>
            </div>
        </div>
    </div>

    @if($user->profile_picture)
    <div class="modal-overlay" id="remove-photo-modal" aria-hidden="true">
        <div class="modal-box">
            <h4>Remove profile picture?</h4>
            <p class="progress-text" style="margin-bottom: 0;">Your profile picture will be removed. You can upload a new one anytime.</p>
            <div class="modal-actions">
                <button type="button" class="btn" id="remove-photo-cancel" style="background: #e5e7eb; color: #374151;">Cancel</button>
                <button type="button" class="btn btn-danger" id="remove-photo-confirm">Remove photo</button>
            </div>
        </div>
    </div>
    @endif

    <script>
        (function() {
            var card = document.getElementById('profile-left-card');
            var toggle = document.getElementById('profile-edit-toggle');
            var saveForm = document.getElementById('profile-save-form');

            if (!card || !toggle) return;

            toggle.addEventListener('click', function() {
            var isEdit = card.classList.contains('profile-edit-mode');

            if (isEdit) {
                card.classList.remove('profile-edit-mode');
                card.classList.add('profile-view-mode');
                if (saveForm) saveForm.style.display = 'none';
                toggle.textContent = "Edit Profile";
            } else {
                card.classList.add('profile-edit-mode');
                card.classList.remove('profile-view-mode');
                if (saveForm) saveForm.style.display = 'block';
                toggle.textContent = "Cancel Editing";
            }
        });
        })();

        (function() {
            var modal = document.getElementById('remove-photo-modal');
            var openBtn = document.getElementById('remove-photo-btn');
            var cancelBtn = document.getElementById('remove-photo-cancel');
            var confirmBtn = document.getElementById('remove-photo-confirm');
            var form = document.getElementById('remove-photo-form');
            if (!modal || !openBtn || !form) return;

            openBtn.addEventListener('click', function() {
                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false');
            });
            function closeModal() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
            }
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (confirmBtn) confirmBtn.addEventListener('click', function() { form.submit(); });
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
        })();
    </script>
</body>
</html>
