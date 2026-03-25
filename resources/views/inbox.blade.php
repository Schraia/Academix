<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')

<style>
/* ── Reset & base ─────────────────────────────────── */
*{margin:0;padding:0;box-sizing:border-box;}
html,body{height:100%;overflow:hidden;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f3f4f6;color:#111827;}

/* ── App shell ────────────────────────────────────── */
.app-shell{display:flex;height:100vh;overflow:hidden;}

/* ── Sidebar (copied from dashboard) ─────────────── */
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
.nav-badge{background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;padding:.1rem .38rem;border-radius:99px;line-height:1.4;}
.nav-logout{margin-top:auto;padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.08);}
.logout-btn{width:100%;padding:.75rem;background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);border-radius:10px;cursor:pointer;font-size:1rem;font-weight:600;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
.logout-btn:hover{background:#fff;color:#b91c1c;transform:translateY(-2px);}
.logout-btn svg{width:20px;height:20px;}

/* ── Inbox layout ─────────────────────────────────── */
.inbox-shell{flex:1;display:flex;flex-direction:column;min-width:0;height:100vh;overflow:hidden;}

/* Toolbar */
.inbox-toolbar{display:flex;align-items:center;gap:.75rem;padding:.6rem 1.2rem;border-bottom:1px solid #e5e7eb;background:#fff;flex-shrink:0;}
.toolbar-group{display:flex;align-items:center;gap:.5rem;}
.toolbar-sep{width:1px;height:22px;background:#e5e7eb;margin:0 .25rem;}
.toolbar-select{border:1px solid #d1d5db;border-radius:8px;padding:.38rem .75rem;font-size:.85rem;background:#fff;cursor:pointer;color:#374151;}
.toolbar-search{flex:1;position:relative;}
.toolbar-search input{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:.38rem .75rem .38rem 2.2rem;font-size:.85rem;background:#f9fafb;}
.toolbar-search .search-icon{position:absolute;left:.65rem;top:50%;transform:translateY(-50%);color:#9ca3af;}
.toolbar-btn{background:none;border:none;cursor:pointer;padding:.35rem;border-radius:8px;color:#6b7280;display:flex;align-items:center;justify-content:center;transition:background .15s,color .15s;}
.toolbar-btn:hover{background:#f3f4f6;color:#111827;}
.toolbar-btn svg{width:18px;height:18px;}
.btn-compose{background:#991b1b;color:#fff;border:none;border-radius:8px;padding:.38rem .9rem;font-size:.85rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.4rem;transition:background .15s;}
.btn-compose:hover{background:#7f1d1d;}
.btn-compose svg{width:16px;height:16px;}

/* Main content area */
.inbox-body{display:flex;flex:1;min-height:0;}

/* Left panel: folder nav + message list */
.inbox-left{width:420px;flex-shrink:0;display:flex;flex-direction:column;border-right:1px solid #e5e7eb;background:#fff;overflow:hidden;}

/* Folder tabs */
.folder-tabs{display:flex;border-bottom:1px solid #e5e7eb;flex-shrink:0;}
.folder-tab{flex:1;padding:.6rem .5rem;text-align:center;font-size:.8rem;font-weight:600;color:#6b7280;border:none;background:none;cursor:pointer;border-bottom:2px solid transparent;transition:all .15s;}
.folder-tab.active{color:#991b1b;border-bottom-color:#991b1b;}

/* Message list */
.msg-list{flex:1;overflow-y:auto;}
.msg-list::-webkit-scrollbar{width:5px;}
.msg-list::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px;}

.msg-item{display:flex;align-items:flex-start;gap:.75rem;padding:.85rem 1rem;border-bottom:1px solid #f3f4f6;cursor:pointer;position:relative;transition:background .12s;}
.msg-item:hover{background:#fef2f2;}
.msg-item.active{background:#fff1f1;}
.msg-item.unread .msg-sender{font-weight:700;}
.msg-item.unread::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:#991b1b;border-radius:0 3px 3px 0;}

.msg-check{flex-shrink:0;margin-top:.15rem;}
.msg-check input[type=checkbox]{accent-color:#991b1b;width:14px;height:14px;cursor:pointer;}

.msg-avatar{width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:#6b7280;}
.msg-avatar img{width:100%;height:100%;border-radius:50%;object-fit:cover;}

.msg-meta{flex:1;min-width:0;}
.msg-sender{font-size:.8rem;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.msg-subject{font-size:.82rem;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin:.1rem 0;}
.msg-preview{font-size:.75rem;color:#9ca3af;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}

.msg-aside{display:flex;flex-direction:column;align-items:flex-end;gap:.3rem;flex-shrink:0;}
.msg-date{font-size:.7rem;color:#9ca3af;white-space:nowrap;}
.msg-count-badge{background:#374151;color:#fff;font-size:.65rem;font-weight:700;padding:.08rem .38rem;border-radius:99px;}
.msg-star{background:none;border:none;cursor:pointer;color:#d1d5db;padding:0;display:flex;}
.msg-star:hover,.msg-star.starred{color:#f59e0b;}
.msg-star svg{width:14px;height:14px;}

.msg-empty{padding:3rem 1rem;text-align:center;color:#9ca3af;font-size:.85rem;}
.msg-empty svg{width:48px;height:48px;margin:0 auto 1rem;display:block;color:#d1d5db;}

/* Right panel: message detail */
.inbox-detail{flex:1;display:flex;flex-direction:column;min-width:0;background:#f9fafb;overflow:hidden;}
.detail-placeholder{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:1rem;color:#9ca3af;}
.detail-placeholder svg{width:80px;height:80px;color:#d1d5db;}
.detail-placeholder p{font-size:1rem;}

.detail-view{flex:1;display:flex;flex-direction:column;overflow:hidden;}
.detail-header{padding:1.2rem 1.5rem;border-bottom:1px solid #e5e7eb;background:#fff;flex-shrink:0;}
.detail-subject{font-size:1.1rem;font-weight:700;color:#111827;}
.detail-meta{display:flex;flex-wrap:wrap;gap:.5rem .75rem;margin-top:.5rem;font-size:.78rem;color:#6b7280;}
.detail-meta span b{color:#374151;}
.detail-body{flex:1;overflow-y:auto;padding:1.5rem;}
.detail-body::-webkit-scrollbar{width:5px;}
.detail-body::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px;}
.detail-message-box{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.2rem 1.5rem;line-height:1.7;font-size:.88rem;white-space:pre-wrap;}
.detail-attachments{margin-top:1rem;}
.detail-attachments h4{font-size:.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.6rem;}
.attachment-chip{display:inline-flex;align-items:center;gap:.45rem;background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:.4rem .75rem;font-size:.78rem;color:#374151;text-decoration:none;margin:.25rem .25rem 0 0;transition:background .15s;}
.attachment-chip:hover{background:#f3f4f6;}
.attachment-chip svg{width:14px;height:14px;color:#991b1b;flex-shrink:0;}

/* ── Compose Modal ─────────────────────────────────── */
.modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;display:none;align-items:flex-start;justify-content:center;padding-top:5vh;}
.modal-backdrop.open{display:flex;}
.modal{background:#fff;border-radius:14px;width:620px;max-width:96vw;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 24px 60px rgba(0,0,0,.25);}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.4rem;border-bottom:1px solid #e5e7eb;}
.modal-title{font-size:1rem;font-weight:700;color:#111827;}
.modal-close{background:none;border:none;cursor:pointer;color:#9ca3af;padding:.25rem;border-radius:6px;display:flex;}
.modal-close:hover{color:#111827;background:#f3f4f6;}
.modal-close svg{width:20px;height:20px;}
.modal-body{padding:1.2rem 1.4rem;overflow-y:auto;flex:1;}
.modal-body::-webkit-scrollbar{width:4px;}
.modal-body::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px;}
#compose-form{display:flex;flex-direction:column;flex:1;min-height:0;overflow:hidden;}
.modal-footer{padding:.85rem 1.4rem;border-top:1px solid #e5e7eb;display:flex;align-items:center;gap:.6rem;flex-shrink:0;}

/* Form elements inside modal */
.form-group{margin-bottom:1rem;}
.form-label{display:block;font-size:.78rem;font-weight:600;color:#374151;margin-bottom:.35rem;}
.form-label .req{color:#dc2626;}
.form-control{width:100%;border:1px solid #d1d5db;border-radius:8px;padding:.5rem .75rem;font-size:.85rem;color:#111827;background:#fff;transition:border .15s;}
.form-control:focus{outline:none;border-color:#991b1b;box-shadow:0 0 0 3px rgba(153,27,27,.1);}
.form-control.error{border-color:#dc2626;}
textarea.form-control{resize:vertical;min-height:100px;}

/* Course row */
.course-row{display:flex;align-items:center;gap:.6rem;}
.course-row select{flex:1;}
.course-x{background:none;border:none;cursor:pointer;color:#9ca3af;padding:.25rem;display:flex;border-radius:6px;}
.course-x:hover{color:#dc2626;background:#fef2f2;}
.course-x svg{width:16px;height:16px;}

/* Individual send checkbox */
.check-row{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:#374151;margin-bottom:1rem;}
.check-row input[type=checkbox]{accent-color:#991b1b;width:14px;height:14px;cursor:pointer;}

/* Recipients field */
.recipients-field{border:1px solid #d1d5db;border-radius:8px;padding:.4rem .6rem;display:flex;flex-wrap:wrap;gap:.35rem;min-height:40px;cursor:text;transition:border .15s;}
.recipients-field:focus-within{border-color:#991b1b;box-shadow:0 0 0 3px rgba(153,27,27,.1);}
.recipients-field.error{border-color:#dc2626;}
.recipient-tag{display:inline-flex;align-items:center;gap:.35rem;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:99px;padding:.18rem .6rem;font-size:.78rem;font-weight:600;}
.recipient-tag button{background:none;border:none;cursor:pointer;color:#991b1b;padding:0;display:flex;line-height:1;}
.recipient-tag button:hover{color:#7f1d1d;}
#recipient-input{border:none;outline:none;font-size:.82rem;flex:1;min-width:120px;padding:.1rem 0;background:transparent;}

/* Role selector */
.role-row{display:flex;gap:.5rem;margin-bottom:.6rem;}
.role-btn{flex:1;padding:.4rem;border:1px solid #d1d5db;border-radius:8px;font-size:.78rem;font-weight:600;cursor:pointer;background:#fff;color:#6b7280;transition:all .15s;}
.role-btn.active{background:#fef2f2;border-color:#991b1b;color:#991b1b;}

/* Autocomplete dropdown */
.autocomplete-wrap{position:relative;}
.autocomplete-list{position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.1);z-index:100;max-height:200px;overflow-y:auto;display:none;}
.autocomplete-list.open{display:block;}
.autocomplete-item{display:flex;align-items:center;gap:.6rem;padding:.55rem .75rem;cursor:pointer;font-size:.82rem;color:#374151;transition:background .1s;}
.autocomplete-item:hover{background:#fef2f2;color:#991b1b;}
.autocomplete-item img,.autocomplete-avatar{width:28px;height:28px;border-radius:50%;object-fit:cover;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:#6b7280;}
.autocomplete-name{font-weight:600;}
.autocomplete-email{font-size:.72rem;color:#9ca3af;}
.autocomplete-empty{padding:.75rem;text-align:center;color:#9ca3af;font-size:.8rem;}

/* Attachment area */
.attach-row{display:flex;align-items:center;gap:.75rem;}
.attach-btn{display:flex;align-items:center;gap:.35rem;background:none;border:1px solid #d1d5db;border-radius:8px;padding:.38rem .75rem;font-size:.78rem;color:#6b7280;cursor:pointer;transition:all .15s;}
.attach-btn:hover{border-color:#991b1b;color:#991b1b;background:#fef2f2;}
.attach-btn svg{width:15px;height:15px;}
.attach-preview{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.5rem;}
.attach-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:.3rem .6rem;font-size:.75rem;color:#374151;}
.attach-chip button{background:none;border:none;cursor:pointer;color:#9ca3af;padding:0;display:flex;}
.attach-chip button:hover{color:#dc2626;}
.attach-chip svg{width:12px;height:12px;}

/* Footer buttons */
.btn-send{background:#991b1b;color:#fff;border:none;border-radius:8px;padding:.5rem 1.2rem;font-size:.88rem;font-weight:700;cursor:pointer;margin-left:auto;transition:background .15s;}
.btn-send:hover{background:#7f1d1d;}
.btn-cancel{background:none;border:1px solid #d1d5db;border-radius:8px;padding:.5rem 1rem;font-size:.88rem;color:#6b7280;cursor:pointer;transition:all .15s;}
.btn-cancel:hover{background:#f3f4f6;}

/* Flash */
.flash{position:fixed;bottom:1.5rem;right:1.5rem;background:#111827;color:#fff;padding:.65rem 1.1rem;border-radius:10px;font-size:.83rem;font-weight:600;z-index:2000;box-shadow:0 8px 20px rgba(0,0,0,.2);transform:translateY(20px);opacity:0;transition:all .3s;}
.flash.show{transform:translateY(0);opacity:1;}
.flash.success{background:#15803d;}
.flash.error{background:#dc2626;}

/* Utility */
.hidden{display:none!important;}

@media(max-width:900px){
    .inbox-left{width:100%;}
    .inbox-detail{display:none;}
    .inbox-detail.mobile-open{display:flex;}
}
</style>
</head>
<body>

<div class="app-shell">

    {{-- ── Sidebar ── --}}
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo">
        </div>

        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('courses.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                <span>Courses</span>
            </a>

            <a href="{{ route('profile.show') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                <span>Profile</span>
            </a>

            <a href="{{ route('inbox.index') }}" class="nav-item active" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                <span>Inbox</span>
            </a>

            @if(!Auth::user()->isAdmin() && !Auth::user()->isInstructor())
            <a href="{{ route('enroll') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                <span>Enroll Online</span>
            </a>
            @endif

            <a href="{{ route('certificates.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                <span>Certificates</span>
            </a>

            @if(Auth::user()->isAdmin())
            <a href="{{ route('settings.index') }}" class="nav-item" style="text-decoration:none;color:inherit;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg>
                <span>Settings</span>
            </a>
            @endif
        </nav>

        <div class="nav-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div><!-- /sidebar -->

    {{-- ── Inbox shell ── --}}
    <div class="inbox-shell">

        {{-- Toolbar --}}
        <div class="inbox-toolbar">
            <button class="btn-compose" id="btn-compose">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                Compose
            </button>

            <div class="toolbar-sep"></div>

            {{-- Course filter --}}
            <select class="toolbar-select" id="filter-course" title="Filter by course">
                <option value="">All Courses</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}">{{ $c->code }} : {{ $c->title }}</option>
                @endforeach
            </select>

            {{-- Folder filter (also reflected as tabs below) --}}
            <select class="toolbar-select" id="filter-folder" title="Folder">
                <option value="inbox"    {{ $folder === 'inbox'    ? 'selected' : '' }}>Inbox</option>
                <option value="sent"     {{ $folder === 'sent'     ? 'selected' : '' }}>Sent</option>
                <option value="starred"  {{ $folder === 'starred'  ? 'selected' : '' }}>Starred</option>
                <option value="archived" {{ $folder === 'archived' ? 'selected' : '' }}>Archived</option>
                <option value="trash"    {{ $folder === 'trash'    ? 'selected' : '' }}>Trash</option>
            </select>

            <div class="toolbar-search">
                <span class="search-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="msg-search" placeholder="Search..." autocomplete="off">
            </div>

            <div class="toolbar-sep"></div>

            {{-- Action buttons --}}
            <button class="toolbar-btn" id="btn-reply"    title="Reply"    style="display:none;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            </button>
            <button class="toolbar-btn" id="btn-reply-all" title="Reply All" style="display:none;">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32z"/></svg>
            </button>
            <button class="toolbar-btn" id="btn-download" title="Download attachments" style="display:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </button>
            <button class="toolbar-btn" id="btn-archive"  title="Archive"       style="display:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </button>
            <button class="toolbar-btn" id="btn-restore"  title="Move to Inbox"  style="display:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </button>
            <button class="toolbar-btn" id="btn-trash"    title="Move to Trash"  style="display:none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div><!-- /toolbar -->

        {{-- Body --}}
        <div class="inbox-body">

            {{-- Left: message list --}}
            <div class="inbox-left">
                <div class="folder-tabs">
                    <button class="folder-tab {{ $folder === 'inbox'    ? 'active' : '' }}" data-folder="inbox">Inbox</button>
                    <button class="folder-tab {{ $folder === 'sent'     ? 'active' : '' }}" data-folder="sent">Sent</button>
                    <button class="folder-tab {{ $folder === 'starred'  ? 'active' : '' }}" data-folder="starred">Starred</button>
                    <button class="folder-tab {{ $folder === 'archived' ? 'active' : '' }}" data-folder="archived">Archived</button>
                    <button class="folder-tab {{ $folder === 'trash'    ? 'active' : '' }}" data-folder="trash">Trash</button>
                </div>

                <div class="msg-list" id="msg-list">
                    @forelse($messages as $msg)
                        @php
                            $isSent = ($folder === 'sent');
                            $isUnread = !$isSent && empty($msg->pivot_read_at);

                            if ($isSent) {
                                $names = $msg->recipientUsers->pluck('name')->take(3)->join(', ');
                                if ($msg->recipientUsers->count() > 3) $names .= ', …';
                                $avatarUser = $msg->recipientUsers->first();
                            } else {
                                $names = $msg->sender->name ?? '(unknown)';
                                $avatarUser = $msg->sender;
                            }

                            $recipientCount = $msg->recipients->count();
                        @endphp

                        <div class="msg-item {{ $isUnread ? 'unread' : '' }}"
                             data-id="{{ $msg->id }}"
                             data-course="{{ $msg->course_id }}"
                             data-subject="{{ $msg->subject ?? '(No subject)' }}"
                             data-sender="{{ $msg->sender->name ?? '' }}"
                             data-body="{{ $msg->body }}"
                             data-date="{{ $msg->created_at->format('M j, Y g:ia') }}"
                             data-course-label="{{ $msg->course ? $msg->course->code . ': ' . $msg->course->title : '' }}"
                             data-recipients="{{ $isSent ? $names : '' }}"
                             data-sender-id="{{ $msg->sender_id }}"
                             data-starred="{{ (!$isSent && !empty($msg->pivot_is_starred)) ? '1' : '0' }}"
                             >

                            <div class="msg-check"><input type="checkbox" aria-label="Select message"></div>

                            <div class="msg-avatar">
                                @if($avatarUser && $avatarUser->profile_picture)
                                    <img src="{{ asset('storage/' . $avatarUser->profile_picture) }}" alt="">
                                @else
                                    {{ strtoupper(substr($names, 0, 1)) }}
                                @endif
                            </div>

                            <div class="msg-meta">
                                <div class="msg-sender">{{ $names }}</div>
                                <div class="msg-subject">{{ $msg->subject ?: '(No subject)' }}</div>
                                <div class="msg-preview">{{ Str::limit($msg->body, 60) }}</div>
                            </div>

                            <div class="msg-aside">
                                <span class="msg-date">{{ $msg->created_at->format('M j, Y') }}</span>
                                @if($recipientCount > 1)
                                    <span class="msg-count-badge">{{ $recipientCount }}</span>
                                @endif
                                <button class="msg-star {{ (!$isSent && !empty($msg->pivot_is_starred)) ? 'starred' : '' }}" aria-label="Star">
                                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="msg-empty">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0l-8 5-8-5"/></svg>
                            <p>No messages here</p>
                        </div>
                    @endforelse
                </div>
            </div><!-- /inbox-left -->

            {{-- Right: detail panel --}}
            <div class="inbox-detail" id="inbox-detail">
                <div class="detail-placeholder" id="detail-placeholder">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <p>No Conversations Selected</p>
                </div>

                <div class="detail-view hidden" id="detail-view">
                    <div class="detail-header">
                        <div class="detail-subject" id="dv-subject"></div>
                        <div class="detail-meta">
                            <span><b>From:</b> <span id="dv-from"></span></span>
                            <span><b>To:</b> <span id="dv-to"></span></span>
                            <span id="dv-course-wrap" class="hidden"><b>Course:</b> <span id="dv-course"></span></span>
                            <span><b>Date:</b> <span id="dv-date"></span></span>
                        </div>
                    </div>
                    <div class="detail-body">
                        <div class="detail-message-box" id="dv-body"></div>
                        <div class="detail-attachments hidden" id="dv-attachments">
                            <h4>Attachments</h4>
                            <div id="dv-attach-chips"></div>
                        </div>
                    </div>
                </div>
            </div><!-- /inbox-detail -->

        </div><!-- /inbox-body -->
    </div><!-- /inbox-shell -->
</div><!-- /app-shell -->

{{-- ── Compose Modal ── --}}
<div class="modal-backdrop" id="compose-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title-label">
    <div class="modal">
        <div class="modal-header">
            <span class="modal-title" id="modal-title-label">Compose Message</span>
            <button class="modal-close" id="modal-close-btn" aria-label="Close">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>

        <form id="compose-form" action="{{ route('inbox.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-body">

                {{-- Course field --}}
                <div class="form-group">
                    <label class="form-label" for="compose-course">Course</label>
                    <div class="course-row">
                        <select class="form-control" id="compose-course" name="course_id">
                            <option value="">– Select a course –</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} : {{ $c->title }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="course-x" id="clear-course" title="Clear course">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Individual send --}}
                <div class="check-row">
                    <input type="checkbox" id="send-individual" name="send_individual" value="1">
                    <label for="send-individual">Send an individual message to each recipient</label>
                </div>

                {{-- Role selector + recipients --}}
                <div class="form-group">
                    <label class="form-label">To <span class="req">*</span></label>

                    {{-- Hidden inputs for selected recipient ids --}}
                    <div id="recipient-id-inputs"></div>

                    <div class="role-row">
                        <button type="button" class="role-btn active" data-role="student"    id="role-student">Student</button>
                        <button type="button" class="role-btn"        data-role="instructor" id="role-instructor">Instructor</button>
                    </div>

                    <div class="autocomplete-wrap">
                        <div class="recipients-field" id="recipients-field">
                            <input type="text" id="recipient-input" placeholder="Insert or Select Names" autocomplete="off" aria-label="Search recipients" aria-autocomplete="list" aria-controls="autocomplete-list">
                        </div>
                        <div class="autocomplete-list" id="autocomplete-list" role="listbox"></div>
                    </div>
                </div>

                {{-- Subject --}}
                <div class="form-group">
                    <label class="form-label" for="compose-subject">Subject</label>
                    <input type="text" class="form-control" id="compose-subject" name="subject" placeholder="Insert Subject" maxlength="255">
                </div>

                {{-- Message --}}
                <div class="form-group">
                    <label class="form-label" for="compose-body">Message <span class="req">*</span></label>
                    <textarea class="form-control" id="compose-body" name="body" rows="6" placeholder="Write your message…"></textarea>
                </div>

                {{-- Attachments --}}
                <div class="form-group">
                    <div class="attach-row">
                        <button type="button" class="attach-btn" id="attach-trigger" title="Attach a file">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                            Attach file
                        </button>
                        <input type="file" id="file-input" name="attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.png,.jpg,.jpeg,.gif,.zip" class="hidden" aria-label="Attach files">
                    </div>
                    <div class="attach-preview" id="attach-preview"></div>
                </div>

            </div><!-- /modal-body -->

            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="modal-cancel-btn">Cancel</button>
                <button type="submit" class="btn-send">Send</button>
            </div>
        </form>
    </div>
</div><!-- /compose-modal -->

{{-- Flash message --}}
@if(session('success'))
<div class="flash success show" id="flash-msg">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="flash error show" id="flash-msg">{{ session('error') }}</div>
@endif

<script>
(function () {
    'use strict';

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const CURRENT_FOLDER = @json($folder);

    // ── Flash auto-hide ─────────────────────────────────
    const flash = document.getElementById('flash-msg');
    if (flash) setTimeout(() => flash.classList.remove('show'), 3500);

    // ── Folder tabs ─────────────────────────────────────
    document.querySelectorAll('.folder-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const folder = tab.dataset.folder;
            const url = new URL(window.location.href);
            url.searchParams.set('folder', folder);
            window.location.href = url.toString();
        });
    });

    document.getElementById('filter-folder').addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('folder', this.value);
        window.location.href = url.toString();
    });

    // ── Message search filter ───────────────────────────
    const searchInput = document.getElementById('msg-search');
    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.msg-item').forEach(item => {
            const text = (item.dataset.subject + item.dataset.sender + item.dataset.body).toLowerCase();
            item.style.display = text.includes(q) ? '' : 'none';
        });
    });

    // ── Course filter ───────────────────────────────────
    document.getElementById('filter-course').addEventListener('change', function () {
        const cid = this.value;
        document.querySelectorAll('.msg-item').forEach(item => {
            item.style.display = (!cid || item.dataset.course == cid) ? '' : 'none';
        });
    });

    // ── Star toggle (persisted via AJAX) ─────────────────
    document.querySelectorAll('.msg-star').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const item = btn.closest('.msg-item');
            if (!item) return;
            fetch(`/inbox/${item.dataset.id}/star`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                btn.classList.toggle('starred', data.starred);
                item.dataset.starred = data.starred ? '1' : '0';
                // Remove from Starred list when unstarring inside that folder
                if (CURRENT_FOLDER === 'starred' && !data.starred) item.remove();
            })
            .catch(() => {});
        });
    });

    // ── Message click → show detail ─────────────────────
    const detailPlaceholder = document.getElementById('detail-placeholder');
    const detailView        = document.getElementById('detail-view');
    const dvSubject         = document.getElementById('dv-subject');
    const dvFrom            = document.getElementById('dv-from');
    const dvTo              = document.getElementById('dv-to');
    const dvCourseWrap      = document.getElementById('dv-course-wrap');
    const dvCourse          = document.getElementById('dv-course');
    const dvDate            = document.getElementById('dv-date');
    const dvBody            = document.getElementById('dv-body');
    const dvAttachments     = document.getElementById('dv-attachments');
    const dvAttachChips     = document.getElementById('dv-attach-chips');

    const btnReply    = document.getElementById('btn-reply');
    const btnReplyAll = document.getElementById('btn-reply-all');
    const btnDownload = document.getElementById('btn-download');
    const btnArchive  = document.getElementById('btn-archive');
    const btnRestore  = document.getElementById('btn-restore');
    const btnTrash    = document.getElementById('btn-trash');

    let activeMessageId = null;

    function openMessage(item) {
        document.querySelectorAll('.msg-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        item.classList.remove('unread');

        activeMessageId = item.dataset.id;

        // Fill detail panel from data attributes (fast, no extra request)
        dvSubject.textContent = item.dataset.subject || '(No subject)';
        dvFrom.textContent    = item.dataset.sender;
        dvDate.textContent    = item.dataset.date;
        dvBody.textContent    = item.dataset.body;

        if (item.dataset.recipients) {
            dvTo.textContent = item.dataset.recipients;
        } else {
            dvTo.textContent = item.dataset.sender;
        }

        if (item.dataset.courseLabel) {
            dvCourse.textContent = item.dataset.courseLabel;
            dvCourseWrap.classList.remove('hidden');
        } else {
            dvCourseWrap.classList.add('hidden');
        }

        // Load full message (attachments) via AJAX
        dvAttachChips.innerHTML = '';
        dvAttachments.classList.add('hidden');

        fetch(`/inbox/${activeMessageId}`, {
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.attachments && data.attachments.length) {
                dvAttachments.classList.remove('hidden');
                data.attachments.forEach(att => {
                    const a = document.createElement('a');
                    a.href = `/inbox/attachments/${att.id}/download`;
                    a.className = 'attachment-chip';
                    a.innerHTML = `<svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a3 3 0 016 0v4a1 1 0 11-2 0V7a1 1 0 00-1-1 1 1 0 00-1 1v4a3 3 0 006 0V7a3 3 0 00-3-3H7z" clip-rule="evenodd"/></svg>${att.filename}`;
                    dvAttachChips.appendChild(a);
                });
            }
        })
        .catch(() => {});

        // Show detail, hide placeholder
        detailPlaceholder.classList.add('hidden');
        detailView.classList.remove('hidden');

        // Show / hide toolbar actions based on the active folder
        const isSentFolder = CURRENT_FOLDER === 'sent';
        const canArchive   = ['inbox', 'starred'].includes(CURRENT_FOLDER);
        const canRestore   = ['trash', 'archived'].includes(CURRENT_FOLDER);
        btnReply.style.display    = isSentFolder ? 'none' : '';
        btnReplyAll.style.display = isSentFolder ? 'none' : '';
        btnDownload.style.display = '';
        btnArchive.style.display  = canArchive ? '' : 'none';
        btnRestore.style.display  = canRestore ? '' : 'none';
        btnTrash.style.display    = canRestore  ? 'none' : '';
    }

    document.querySelectorAll('.msg-item').forEach(item => {
        item.addEventListener('click', e => {
            if (e.target.closest('.msg-check') || e.target.closest('.msg-star')) return;
            openMessage(item);
        });
    });

    // ── Remove active message helper ──────────────────────
    function removeActiveMessage() {
        const item = document.querySelector(`.msg-item[data-id="${activeMessageId}"]`);
        if (item) item.remove();
        detailView.classList.add('hidden');
        detailPlaceholder.classList.remove('hidden');
        [btnReply, btnReplyAll, btnDownload, btnArchive, btnRestore, btnTrash].forEach(b => b.style.display = 'none');
        activeMessageId = null;
    }

    // ── Trash button ────────────────────────────────────
    btnTrash.addEventListener('click', () => {
        if (!activeMessageId) return;
        if (!confirm('Move this message to trash?')) return;

        fetch(`/inbox/${activeMessageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(() => { removeActiveMessage(); showFlash('Message moved to trash', 'success'); })
        .catch(()  => showFlash('Something went wrong', 'error'));
    });

    // ── Archive button ────────────────────────────────────
    btnArchive.addEventListener('click', () => {
        if (!activeMessageId) return;
        fetch(`/inbox/${activeMessageId}/archive`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(() => { removeActiveMessage(); showFlash('Message archived', 'success'); })
        .catch(()  => showFlash('Something went wrong', 'error'));
    });

    // ── Restore button (from trash / archived → inbox) ────
    btnRestore.addEventListener('click', () => {
        if (!activeMessageId) return;
        fetch(`/inbox/${activeMessageId}/restore`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(() => { removeActiveMessage(); showFlash('Message restored to inbox', 'success'); })
        .catch(()  => showFlash('Something went wrong', 'error'));
    });

    // ── Reply shortcut → opens compose pre-filled ───────
    btnReply.addEventListener('click', () => {
        const item = document.querySelector(`.msg-item[data-id="${activeMessageId}"]`);
        if (!item) return;
        openCompose({
            courseId: item.dataset.course,
            recipientId: item.dataset.senderId,
            recipientName: item.dataset.sender,
            subject: 'Re: ' + (item.dataset.subject || ''),
        });
    });

    // ── Flash helper ────────────────────────────────────
    function showFlash(msg, type) {
        let el = document.getElementById('flash-msg');
        if (!el) {
            el = document.createElement('div');
            el.id = 'flash-msg';
            el.className = 'flash';
            document.body.appendChild(el);
        }
        el.textContent = msg;
        el.className = `flash ${type} show`;
        setTimeout(() => el.classList.remove('show'), 3500);
    }

    // ══════════════════════════════════════════
    // ── Compose modal ──────────────────────────
    // ══════════════════════════════════════════

    const modal          = document.getElementById('compose-modal');
    const composeForm    = document.getElementById('compose-form');
    const composeCourse  = document.getElementById('compose-course');
    const recipientsField = document.getElementById('recipients-field');
    const recipientInput = document.getElementById('recipient-input');
    const autoList       = document.getElementById('autocomplete-list');
    const idInputs       = document.getElementById('recipient-id-inputs');

    let selectedRecipients = []; // [{id, name}]
    let currentRole = 'student';
    let debounceTimer = null;

    function openCompose(prefill) {
        prefill = prefill || {};
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';

        // Reset
        composeForm.reset();
        selectedRecipients = [];
        renderTags();
        idInputs.innerHTML = '';
        document.getElementById('attach-preview').innerHTML = '';
        document.getElementById('file-input').value = '';

        if (prefill.courseId) composeCourse.value = prefill.courseId;
        if (prefill.subject)  document.getElementById('compose-subject').value = prefill.subject;
        if (prefill.recipientId && prefill.recipientName) {
            addRecipient({ id: prefill.recipientId, name: prefill.recipientName });
        }

        recipientInput.focus();
    }

    function closeCompose() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('btn-compose').addEventListener('click',       () => openCompose());
    document.getElementById('modal-close-btn').addEventListener('click',   closeCompose);
    document.getElementById('modal-cancel-btn').addEventListener('click',  closeCompose);
    modal.addEventListener('click', e => { if (e.target === modal) closeCompose(); });

    document.getElementById('clear-course').addEventListener('click', () => {
        composeCourse.value = '';
        autoList.innerHTML = '';
        autoList.classList.remove('open');
    });

    // ── Role buttons ────────────────────────────────────
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentRole = btn.dataset.role;
            recipientInput.value = '';
            autoList.innerHTML = '';
            autoList.classList.remove('open');
            if (composeCourse.value) fetchUsers('');
        });
    });

    // ── Autocomplete ─────────────────────────────────────
    composeCourse.addEventListener('change', () => {
        autoList.innerHTML = '';
        autoList.classList.remove('open');
        if (composeCourse.value) fetchUsers(recipientInput.value);
    });

    recipientInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchUsers(this.value), 250);
    });

    recipientInput.addEventListener('focus', function () {
        if (composeCourse.value) fetchUsers(this.value);
    });

    function fetchUsers(search) {
        const courseId = composeCourse.value;
        if (!courseId) {
            autoList.innerHTML = '<div class="autocomplete-empty">Select a course first</div>';
            autoList.classList.add('open');
            return;
        }

        const params = new URLSearchParams({ course_id: courseId, role: currentRole, search });
        fetch(`/inbox/course-users?${params}`, {
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(users => {
            const already = selectedRecipients.map(r => r.id);
            const filtered = users.filter(u => !already.includes(u.id));
            renderAutoList(filtered);
        })
        .catch(() => {});
    }

    function renderAutoList(users) {
        autoList.innerHTML = '';
        if (!users.length) {
            autoList.innerHTML = '<div class="autocomplete-empty">No results found</div>';
        } else {
            users.forEach(u => {
                const div = document.createElement('div');
                div.className = 'autocomplete-item';
                div.setAttribute('role', 'option');
                const initial = (u.name || '?').charAt(0).toUpperCase();
                const avatarHtml = u.profile_picture
                    ? `<img src="/storage/${u.profile_picture}" alt="" class="autocomplete-avatar">`
                    : `<div class="autocomplete-avatar">${initial}</div>`;
                div.innerHTML = `${avatarHtml}<div><div class="autocomplete-name">${escHtml(u.name)}</div><div class="autocomplete-email">${escHtml(u.email)}</div></div>`;
                div.addEventListener('mousedown', e => {
                    e.preventDefault();
                    addRecipient(u);
                    recipientInput.value = '';
                    autoList.classList.remove('open');
                });
                autoList.appendChild(div);
            });
        }
        autoList.classList.add('open');
    }

    recipientInput.addEventListener('blur', () => {
        setTimeout(() => autoList.classList.remove('open'), 150);
    });

    function addRecipient(user) {
        if (selectedRecipients.find(r => r.id == user.id)) return;
        selectedRecipients.push({ id: user.id, name: user.name });
        renderTags();
    }

    function removeRecipient(id) {
        selectedRecipients = selectedRecipients.filter(r => r.id != id);
        renderTags();
    }

    function renderTags() {
        // Clear old tags (keep the input)
        Array.from(recipientsField.children).forEach(c => {
            if (c !== recipientInput) c.remove();
        });
        idInputs.innerHTML = '';

        selectedRecipients.forEach(r => {
            const tag = document.createElement('span');
            tag.className = 'recipient-tag';
            tag.innerHTML = `${escHtml(r.name)}<button type="button" aria-label="Remove ${escHtml(r.name)}">&times;</button>`;
            tag.querySelector('button').addEventListener('click', () => removeRecipient(r.id));
            recipientsField.insertBefore(tag, recipientInput);

            const hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'recipient_ids[]';
            hidden.value = r.id;
            idInputs.appendChild(hidden);
        });
    }

    recipientsField.addEventListener('click', () => recipientInput.focus());

    // ── File attachments ─────────────────────────────────
    const fileInput     = document.getElementById('file-input');
    const attachTrigger = document.getElementById('attach-trigger');
    const attachPreview = document.getElementById('attach-preview');

    attachTrigger.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function () {
        attachPreview.innerHTML = '';
        Array.from(this.files).forEach(f => {
            const chip = document.createElement('div');
            chip.className = 'attach-chip';
            chip.innerHTML = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>${escHtml(f.name)}`;
            attachPreview.appendChild(chip);
        });
    });

    // ── Form validation ──────────────────────────────────
    composeForm.addEventListener('submit', function (e) {
        let ok = true;
        const body = document.getElementById('compose-body');
        const rf   = document.getElementById('recipients-field');

        body.classList.remove('error');
        rf.classList.remove('error');

        if (!selectedRecipients.length) {
            rf.classList.add('error');
            ok = false;
        }
        if (!body.value.trim()) {
            body.classList.add('error');
            ok = false;
        }
        if (!ok) {
            e.preventDefault();
            showFlash('Please fill in required fields', 'error');
        }
    });

    // ── Keyboard: Escape closes modal ────────────────────
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeCompose();
    });

    // ── Helper: escape HTML ───────────────────────────────
    function escHtml(str) {
        return String(str || '')
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }

})();
</script>

</body>
</html>
