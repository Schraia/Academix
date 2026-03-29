<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    @vite('resources/css/app.css')

    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        html,body{height:100%;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f3f4f6;color:#111827;}

        .app-shell{display:flex;height:100vh;overflow:hidden;}

        /* Sidebar (aligned with other pages) */
        .sidebar{width:260px;height:100vh;position:sticky;top:0;flex-shrink:0;
            background:linear-gradient(180deg,#962121 0%,#991b1b 40%,#450a0a 100%);
            color:rgba(255,255,255,.92);display:flex;flex-direction:column;
            box-shadow:8px 0 40px rgba(0,0,0,.35);overflow:hidden;}
        .sidebar::after{content:"";position:absolute;inset:0;
            background:radial-gradient(circle at 20% 10%,rgba(255,255,255,.05),transparent 40%),
                       radial-gradient(circle at 80% 30%,rgba(255,255,255,.04),transparent 40%);
            pointer-events:none;}
        .sidebar-header{padding:2rem 1.5rem;border-bottom:1px solid rgba(255,255,255,.1);display:flex;justify-content:center;align-items:center;}
        .sidebar-logo{max-width:140px;filter:drop-shadow(0 6px 12px rgba(0,0,0,.4));}
        .nav-menu{flex:1;min-height:0;overflow-y:auto;padding:1rem 0;}
        .nav-item{padding:.9rem 1.75rem;cursor:pointer;display:flex;align-items:center;gap:.9rem;font-weight:500;font-size:.95rem;position:relative;transition:all .25s ease;text-decoration:none;color:inherit;}
        .nav-item:hover{background:rgba(255,255,255,.08);padding-left:2.1rem;}
        .nav-item svg{width:19px;height:19px;opacity:.85;transition:all .25s ease;flex-shrink:0;}
        .nav-item:hover svg{opacity:1;transform:scale(1.15);}
        .nav-item.active{background:rgba(255,255,255,.12);}
        .nav-item.active::before{content:"";position:absolute;left:0;top:0;height:100%;width:6px;background:linear-gradient(180deg,#ef4444,#fff);border-radius:0 6px 6px 0;}
        .nav-logout{margin-top:auto;padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.08);}
        .logout-btn{width:100%;padding:.75rem;background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:10px;cursor:pointer;font-size:1rem;font-weight:600;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
        .logout-btn:hover{background:#fff;color:#b91c1c;transform:translateY(-2px);}
        .logout-btn svg{width:20px;height:20px;}

        /* Page */
        .page{flex:1;display:flex;flex-direction:column;min-width:0;height:100vh;overflow:hidden;}
        .toolbar{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.3rem;background:#fff;border-bottom:1px solid #e5e7eb;flex-shrink:0;}
        .toolbar h1{font-size:1.2rem;font-weight:900;letter-spacing:-.02em;}
        .pill{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .65rem;border-radius:999px;background:#fef2f2;color:#b91c1c;font-size:.78rem;font-weight:800;border:1px solid #fee2e2;}
        .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.45rem .85rem;border-radius:10px;border:1px solid #e5e7eb;background:#fff;color:#111827;text-decoration:none;font-weight:800;font-size:.85rem;transition:.15s;}
        .btn:hover{background:#f9fafb;}
        .content{flex:1;min-height:0;overflow:auto;padding:1rem 1.3rem;}
        .content::-webkit-scrollbar{width:6px;}
        .content::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:6px;}

        .list{display:flex;flex-direction:column;gap:.75rem;}
        .item{display:block;background:#fff;border:1px solid rgba(229,231,235,.95);border-radius:16px;padding:.85rem 1rem;box-shadow:0 10px 22px rgba(17,24,39,0.06);text-decoration:none;color:inherit;transition:.15s;}
        .item:hover{transform:translateY(-1px);box-shadow:0 16px 32px rgba(17,24,39,0.10);}
        .item.unread{border-color:#fecaca;box-shadow:0 10px 22px rgba(220,38,38,0.10);}
        .item-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;}
        .title{font-weight:950;}
        .meta{font-size:.78rem;color:#9ca3af;white-space:nowrap;}
        .message{margin-top:.35rem;color:#6b7280;font-size:.9rem;line-height:1.35;}
        .empty{background:#fff;border:1px dashed #e5e7eb;border-radius:16px;padding:2rem;text-align:center;color:#6b7280;}
    </style>
</head>
<body>
<div class="app-shell">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo">
        </div>

        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('notifications.index') }}" class="nav-item active">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22Zm7-6V11a7 7 0 1 0-14 0v5l-2 2v1h18v-1l-2-2Z"/>
                </svg>
                <span>Notifications</span>
            </a>

            <a href="{{ route('courses.index') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('profile.show') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                </svg>
                <span>Profile</span>
            </a>

            <a href="{{ route('inbox.index') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
                <span>Inbox</span>
            </a>
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

    <div class="page">
        <div class="toolbar">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <h1>Notifications</h1>
                <span class="pill">{{ $unreadCount }} unread</span>
            </div>
            <a href="{{ route('dashboard') }}" class="btn">Back to Dashboard</a>
        </div>

        <div class="content">
            @if($notifications->count() === 0)
                <div class="empty">No notifications yet.</div>
            @else
                <div class="list">
                    @foreach($notifications as $n)
                        <a class="item {{ $n->read_at ? '' : 'unread' }}" href="{{ route('notifications.go', $n) }}">
                            <div class="item-top">
                                <div class="title">{{ $n->title }}</div>
                                <div class="meta">{{ $n->created_at->format('M j, Y g:i A') }}</div>
                            </div>
                            @if($n->message)
                                <div class="message">{{ $n->message }}</div>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div style="margin-top:1rem;">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>

