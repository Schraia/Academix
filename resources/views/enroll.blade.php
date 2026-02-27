<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(
        135deg,
        #f9fafb 0%,
        #eef2f7 40%,
        #e5e7eb 100%
    );
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
        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
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
    padding: 3rem;
    display: flex;
    gap: 2rem;
    position: relative;
}


.main-content::before {
    content: '';
    position: absolute;
    top: 50px;
    right: 80px;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(185,28,28,0.18), transparent 70%);
    z-index: 0;
    pointer-events: none;
}
        .course-selection {
            width: 33.333%;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.85);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.06),
                0 2px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.4);
            position: relative;
    z-index: 1;
        }
        .sections-container {
            width: 66.666%;
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.85);
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.06),
                0 2px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.4);
            position: relative;
    z-index: 1;
        }
        .sections-container.visible {
            display: block;
        }
        .enroll-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 2rem;
        }
        .sections-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }
        .sections-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .section-item {
    padding: 1.5rem 1.75rem;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    transition: all 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 2rem;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
}

.section-item:hover {
    transform: translateY(-6px);
    border-color: #b91c1c;
    box-shadow: 0 14px 35px rgba(185, 28, 28, 0.18);
}
        .section-item-content {
            flex: 1;
        }
        .section-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .section-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.3rem;
        }

        .section-info {
            font-size: 0.85rem;
            color: #9ca3af;
        }
        .section-details {
            display: flex;
            gap: 3rem;
            margin-top: 1rem;
        }

        .section-detail-item {
            display: flex;
            flex-direction: column;
        }

        .section-detail-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .section-detail-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #374151;
        }
        .section-item-actions {
            flex-shrink: 0;
        }
        .btn-enroll,
        .btn-view,
        .btn-options,
        .btn-remove {
            padding: 0.55rem 1.3rem;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .btn-enroll {
            background: #16a34a;
            color: white;
                }
        .btn-enroll:hover:not(:disabled) {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(22,163,74,0.35);
        }
        .btn-enroll:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .btn-enroll.enrolled {
            background: #ef4444;
        }
        .btn-enroll.enrolled:hover:not(:disabled) {
            background: #dc2626;
        }
        .btn-view,
        .btn-options {
            background: #b91c1c;
            color: white;
        }
        .btn-options {
            padding: 0.5rem 1rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.3s;
        }
        .btn-options:hover:not(:disabled) {
            background: #1d4ed8;
        }
        .btn-options:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .btn-options.enrolled {
            background: #16a34a;
        }
        .btn-remove,
.btn-enroll.enrolled {
    background: #ef4444;
}
.btn-remove:hover,
.btn-enroll.enrolled:hover {
    background: #dc2626;
    box-shadow: 0 8px 20px rgba(239,68,68,0.35);
}


button:disabled {
    background: #9ca3af !important;
    cursor: not-allowed;
    box-shadow: none !important;
}
        .btn-remove:hover { background: #dc2626; }
        .enroll-modal-details { margin: 1rem 0; }
        .enroll-modal-details p { margin: 0.5rem 0; color: #374151; }
        .enroll-modal-actions { display: flex; gap: 0.75rem; margin-top: 1rem; align-items: center; }
        .conflict-warning { color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem; }
        .enroll-modal-sections { display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem; }
        .enroll-modal-section-card {
            padding: 1rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;
            display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .enroll-modal-section-card .section-card-left { flex: 1; min-width: 0; }
        .enroll-modal-section-card .section-card-name { font-weight: 600; color: #1f2937; margin-bottom: 0.25rem; }
        .enroll-modal-section-card .section-card-meta { font-size: 0.875rem; color: #6b7280; }
        .enroll-modal-section-card .section-card-meta span { margin-right: 1rem; }
        .enroll-modal-section-card .section-card-actions { flex-shrink: 0; }
        .enroll-modal-section-card--conflict { background: #fef2f2; border-color: #fecaca; }
        #enrollModal .modal-box { max-width: 520px; }
        #mlcModal .modal-box { max-width: 520px; }
        #peModal .modal-box { max-width: 520px; }
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        .modal-overlay.visible {
            display: flex;
        }
        .modal-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
        }
        .modal-options {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .modal-option {
            padding: 0.75rem 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .modal-option:hover {
            background: #eff6ff;
            border-color: #2563eb;
        }
        .modal-option.current-pe {
            background: #dcfce7;
            border-color: #16a34a;
        }
        .modal-close {
    padding: 0.55rem 1.3rem;
    background: #6b7280;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
}
        .modal-close:hover {
            background: #4b5563;
        }
        .restriction-message { color: #374151; margin: 0 0 1rem; line-height: 1.5; }
        .modal-box-k12 { max-width: 480px; }
        .k12-subjects { margin: 1rem 0; }
        .k12-subject-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.75rem; background: #f9fafb; border-radius: 8px; margin-bottom: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .k12-subject-name { font-weight: 600; color: #1f2937; }
        .k12-subject-time { color: #6b7280; font-size: 0.9375rem; }
        .k12-modal-actions { display: flex; gap: 0.75rem; margin-top: 1rem; align-items: center; }
        .btn-view {
            padding: 0.5rem 1rem;
            background: #b91c1c;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .btn-view:hover,
        .btn-options:hover {
            background: #7f1d1d;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185,28,28,0.35);
        }
        .btn-view.enrolled { background: #16a34a; }
        .save-enrollments-wrap {
            margin-top: 1.5rem;
            display: none;
        }
        .save-enrollments-wrap.visible {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .btn-save-enrollments {
            padding: 0.75rem 1.5rem;
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .btn-save-enrollments:hover {
            background: #15803d;
        }
        .btn-clear-all-enrollments {
            padding: 0.75rem 1.5rem;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.3s;
        }
        .btn-clear-all-enrollments:hover {
            background: #4b5563;
        }
        .course-category {
            margin-bottom: 1rem;
        }
        .course-category .sub-subcategory {
            position: relative;
            transition: all 0.25s ease;
        }

        .course-category .sub-subcategory.selected {
            font-weight: 600;
            color: #b91c1c;
            border-left: 3px solid #b91c1c !important;
            padding-left: 1.25rem !important;

            background: rgba(185, 28, 28, 0.06);
            box-shadow: 0 0 0 1px rgba(185, 28, 28, 0.15),
                        0 4px 12px rgba(185, 28, 28, 0.15);
        }

        .course-category .sub-subcategory.selected:hover {
            background: rgba(185, 28, 28, 0.1);
            box-shadow: 0 0 0 1px rgba(185, 28, 28, 0.25),
                        0 6px 18px rgba(185, 28, 28, 0.25);
        }
        .category-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 1.2rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .category-header:hover {
            border-color: #ef4444;
            box-shadow: 0 6px 18px rgba(239, 68, 68, 0.15);
            transform: translateY(-2px);
        }

        .category-header.active {
            background: linear-gradient(90deg, #fee2e2, #ffffff);
            border-color: #ef4444;
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
        }
        .category-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: 0.3px;
        }

        .category-subtitle {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-left: 0.5rem;
            font-weight: 500;
        }
        .caret {
            width: 20px;
            height: 20px;
            transition: transform 0.3s;
            color: #6b7280;
            transform: rotate(0deg);
        }
        .caret.expanded {
            transform: rotate(90deg);
        }
        .category-content {
            max-height: 0;
            overflow: hidden;
            transition: all 0.4s ease;
            margin-top: 0.75rem;
            padding-left: 0.75rem;
            opacity: 0;
            transform: translateY(-5px);
        }

        .category-content.expanded {
            max-height: 1000px;
            opacity: 1;
            transform: translateY(0);
        }
        .sub-subcategory {
            position: relative;
            padding: 0.9rem 1.2rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.25s ease;
            font-weight: 500;
            color: #374151;
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
            overflow: hidden;
        }

      
        .sub-subcategory::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            transition: all 0.25s ease;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }
        .subcategory-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .subcategory-header:hover {
            background: #f9fafb;
        }
        .subcategory-title {
            font-size: 1rem;
            font-weight: 500;
            color: #374151;
        }
        .subcategory-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-top: 0.5rem;
            padding-left: 1rem;
        }
        .subcategory-content.expanded {
            max-height: 1000px;
        }
        .sub-subcategory {
            padding: 0.75rem 1rem;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .sub-subcategory:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .sub-subcategory.selected {
            background: #fee2e2;
            border-color: #ef4444;
        }
        .sub-subcategory-title {
            font-size: 0.9375rem;
            color: #4b5563;
            font-weight: 500;
        }
        .course-category .sub-subcategory,
        .course-category .sub-subcategory .sub-subcategory-title {
            font-weight: 600 !important;
            color: #111827 !important;   
        }

        .course-category .sub-subcategory:hover {
            color: #7f1d1d !important;
        }

        .course-category .sub-subcategory.selected {
            font-weight: 700 !important;
            color: #7f1d1d !important;
        }

        
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
    <img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo">
</div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item" style="text-decoration: none; color: inherit;">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Courses</span>
                </a>
                <div class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span>Users</span>
                </div>
                <div class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                    </svg>
                    <span>Profile</span>
                </div>
                <div class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Enroll Online</span>
                </div>
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
            <div class="course-selection">
                <h1 class="enroll-title">Enroll For:</h1>
                
                @if(empty($restrictToCollege))
                <div class="course-category" data-category="primary">
                    <div class="category-header" onclick="toggleCategory(this, 'primary')">
                        <div>
                            <span class="category-title">Primary Courses</span>
                            <span class="category-subtitle">(K-10)</span>
                        </div>
                        <svg class="caret" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="category-content">
                        <div class="sub-subcategory" data-course-name="Kinder" onclick="selectCourse(this, 'Kinder')">
                            <span class="sub-subcategory-title">Kinder</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 1" onclick="selectCourse(this, 'Grade 1')">
                            <span class="sub-subcategory-title">Grade 1</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 2" onclick="selectCourse(this, 'Grade 2')">
                            <span class="sub-subcategory-title">Grade 2</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 3" onclick="selectCourse(this, 'Grade 3')">
                            <span class="sub-subcategory-title">Grade 3</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 4" onclick="selectCourse(this, 'Grade 4')">
                            <span class="sub-subcategory-title">Grade 4</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 5" onclick="selectCourse(this, 'Grade 5')">
                            <span class="sub-subcategory-title">Grade 5</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 6" onclick="selectCourse(this, 'Grade 6')">
                            <span class="sub-subcategory-title">Grade 6</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 7" onclick="selectCourse(this, 'Grade 7')">
                            <span class="sub-subcategory-title">Grade 7</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 8" onclick="selectCourse(this, 'Grade 8')">
                            <span class="sub-subcategory-title">Grade 8</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 9" onclick="selectCourse(this, 'Grade 9')">
                            <span class="sub-subcategory-title">Grade 9</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 10" onclick="selectCourse(this, 'Grade 10')">
                            <span class="sub-subcategory-title">Grade 10</span>
                        </div>
                    </div>
                </div>

                <div class="course-category" data-category="secondary">
                    <div class="category-header" onclick="toggleCategory(this, 'secondary')">
                        <div>
                            <span class="category-title">Secondary Courses</span>
                            <span class="category-subtitle">(SHS)</span>
                        </div>
                        <svg class="caret" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="category-content">
                        <div class="sub-subcategory" data-course-name="Grade 11" onclick="selectCourse(this, 'Grade 11')">
                            <span class="sub-subcategory-title">Grade 11</span>
                        </div>
                        <div class="sub-subcategory" data-course-name="Grade 12" onclick="selectCourse(this, 'Grade 12')">
                            <span class="sub-subcategory-title">Grade 12</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="course-category" data-category="college">
                    <div class="category-header" onclick="toggleCategory(this, 'college')">
                        <div>
                            <span class="category-title">College Courses</span>
                        </div>
                        <svg class="caret" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="category-content">
                        @php
                            $collegeSemesters = [
                                '1st Year, 1st Semester', '1st Year, 2nd Semester',
                                '2nd Year, 1st Semester', '2nd Year, 2nd Semester',
                                '3rd Year, 1st Semester', '3rd Year, 2nd Semester',
                                '4th Year, 1st Semester', '4th Year, 2nd Semester',
                            ];
                        @endphp
                        @foreach($collegeCourses ?? [] as $cc)
                        <div class="subcategory">
                            <div class="subcategory-header" onclick="toggleSubcategory(this)">
                                <span class="subcategory-title">{{ $cc->name }}</span>
                                @if($cc->code)<span class="category-subtitle">({{ $cc->code }})</span>@endif
                                <svg class="caret" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="subcategory-content">
                                @foreach($collegeSemesters as $sem)
                                @php $courseNameSem = $cc->name . ' - ' . $sem; @endphp
                                <div class="sub-subcategory" data-course-name="{{ $courseNameSem }}" data-college-course-id="{{ $cc->id }}" onclick="selectCourseWithCollege(this, {{ json_encode($courseNameSem) }}, {{ $cc->id }})">
                                    <span class="sub-subcategory-title">{{ $sem }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sections-container" id="sectionsContainer">
                @if(session('error'))
                    <p style="color: #dc2626; margin-bottom: 1rem;">{{ session('error') }}</p>
                @endif
                @if(session('info'))
                    <p style="color: #2563eb; margin-bottom: 1rem;">{{ session('info') }}</p>
                @endif
                <h2 class="sections-title" id="sectionsTitle">Available Sections</h2>
                <div class="sections-list" id="sectionsList">
                    <!-- Sections will be populated here -->
                </div>
                <div class="save-enrollments-wrap" id="saveEnrollmentsWrap">
                    <form id="saveEnrollmentsForm" method="POST" action="{{ route('enroll.save') }}">
                        @csrf
                        <input type="hidden" name="items" id="enrollItemsInput" value="">
                        <input type="hidden" name="return_course_name" id="returnCourseNameInput" value="">
                        <input type="hidden" name="return_college_course_id" id="returnCollegeCourseIdInput" value="">
                        <button type="submit" class="btn-save-enrollments">Save</button>
                    </form>
                    <button type="button" class="btn-clear-all-enrollments" id="clearAllEnrollmentsBtn" onclick="clearAllEnrollments()">Clear All</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="peModal">
        <div class="modal-box">
            <div class="modal-title" id="peModalTitle">Choose PE subject</div>
            <div class="enroll-modal-sections" id="peModalOptions"></div>
            <button type="button" class="modal-close" onclick="closePeModal()">Close</button>
        </div>
    </div>

    <div class="modal-overlay" id="k12Modal">
        <div class="modal-box modal-box-k12">
            <div class="modal-title" id="k12ModalTitle">Section</div>
            <div class="k12-subjects" id="k12ModalSubjects"></div>
            <div class="k12-modal-actions">
                <button type="button" class="btn-enroll" id="k12ModalEnrollBtn" onclick="toggleK12Enroll()">Enroll</button>
                <button type="button" class="modal-close" onclick="closeK12Modal()">Close</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="enrollModal">
        <div class="modal-box">
            <div class="modal-title" id="enrollModalTitle">CCS 1101 - Fundamentals of Programming</div>
            <div class="enroll-modal-sections" id="enrollModalSections">
                <!-- Section cards filled by JS: Section name, Time frame, Enrolled count, Available, Enroll button -->
            </div>
            <p class="conflict-warning" id="enrollModalConflict" style="display:none;"></p>
            <div class="enroll-modal-actions" style="margin-top: 1rem;">
                <button type="button" class="modal-close" onclick="closeEnrollModal()">Close</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="mlcModal">
        <div class="modal-box">
            <div class="modal-title" id="mlcModalTitle">Choose MLC track</div>
            <div class="enroll-modal-sections" id="mlcModalOptions"></div>
            <button type="button" class="modal-close" onclick="closeMlcModal()">Close</button>
        </div>
    </div>

    <div class="modal-overlay" id="restrictionModal">
        <div class="modal-box">
            <div class="modal-title">Enrollment restriction</div>
            <p id="restrictionModalMessage" class="restriction-message"></p>
            <button type="button" class="modal-close" onclick="closeRestrictionModal()">OK</button>
        </div>
    </div>

    <script>
        window.alreadyEnrolled = @json($alreadyEnrolled ?? []);
        window.curriculumByCollege = @json($curriculumByCollege ?? []);
        window.sectionsByCollege = @json($sectionsByCollege ?? []);
        window.sectionSubjectTimes = @json($sectionSubjectTimes ?? []);
        window.peMlcSchedules = @json($peMlcSchedules ?? ['pe' => [], 'mlc' => []]);
        window.pendingEnrollmentsFromSession = @json($pendingEnrollmentsFromSession ?? []);
        window.returnCourseName = @json($returnCourseName ?? '');
        window.returnCollegeCourseId = @json($returnCollegeCourseId ?? '');
        let currentSelectedCategory = null;
        let currentSelectedCourse = null;
        let currentCourseName = null;
        let currentCollegeCourseId = null;
        let enrolledItems = [];

        var PE_OPTIONS = ['Badminton', 'Volleyball', 'Basketball', 'Table Tennis', 'Swimming', 'Dance'];
        var MLC_OPTIONS = ['Literacy (STC)', 'Civic Welfare (STL)', 'Military Science (STM)'];
        var K12_SECTION_NAMES_BY_GRADE = [
            ['Argento', 'Oro', 'Bronce'],
            ['Plata', 'Cobre', 'Estano'],
            ['Hierro', 'Acero', 'Cobalto'],
            ['Niquel', 'Zinc', 'Laton'],
            ['Mercurio', 'Cinabrio', 'Azurita'],
            ['Esmeralda', 'Rubi', 'Zafiro'],
            ['Ambar', 'Jade', 'Coral'],
            ['Marfil', 'Ebano', 'Granate'],
            ['Topacio', 'Perla', 'Onix'],
            ['Opalo', 'Cuarzo', 'Amatista'],
            ['Aguamarina', 'Turquesa', 'Lapislazuli'],
            ['Citrino', 'Cornalina', 'Obsidiana'],
            ['Hematite', 'Malquita', 'Azabache']
        ];
        var K12_SUBJECTS = [
            { name: 'Science', time: '8:00 AM' },
            { name: 'Math', time: '9:00 AM' },
            { name: 'English', time: '10:00 AM' },
            { name: 'Filipino', time: '11:00 AM' }
        ];

        function isK12Course(courseName) {
            if (courseName === 'Kinder') return true;
            for (var g = 1; g <= 12; g++) if (courseName === 'Grade ' + g) return true;
            return false;
        }

        function getK12GradeIndex(courseName) {
            if (courseName === 'Kinder') return 0;
            var m = courseName.match(/^Grade (\d+)$/);
            return m ? parseInt(m[1], 10) : 0;
        }

        function parseCollegeYearSemester(courseName) {
            var match = courseName.match(/\s-\s(\d)(?:st|nd|rd|th)\sYear,\s(\d)(?:st|nd|rd|th)\sSemester$/);
            if (!match) return null;
            return { year: match[1], semester: match[2] };
        }
        function parseSemesterLabel(sectionName) {
            var match = sectionName.match(/(\d)(?:st|nd|rd|th)\sYear,\s(\d)(?:st|nd|rd|th)\sSemester/);
            if (!match) return null;
            return { year: match[1], semester: match[2] };
        }

        function getSectionsForCourse(courseName) {
            if (isK12Course(courseName)) {
                var idx = getK12GradeIndex(courseName);
                var names = K12_SECTION_NAMES_BY_GRADE[idx] || K12_SECTION_NAMES_BY_GRADE[0];
                return names.map(function(sn) { return { sectionName: sn, isK12: true }; });
            }
            if (currentCollegeCourseId && window.curriculumByCollege) {
                var parsed = parseCollegeYearSemester(courseName);
                if (parsed) {
                    var ccId = String(currentCollegeCourseId);
                    var byYear = window.curriculumByCollege[ccId];
                    if (!byYear) return [];
                    var bySem = byYear[parsed.year];
                    if (!bySem) return [];
                    var subjects = bySem[parsed.semester];
                    if (!subjects || !subjects.length) return [];
                    var sectionLabel = courseName.indexOf(' - ') >= 0 ? courseName.split(' - ').slice(1).join(' - ') : courseName;
                    var mapped = subjects.map(function(subj) {
                        return {
                            courseCode: subj.code,
                            courseName: subj.title,
                            sectionName: sectionLabel,
                            credits: subj.credits || 0,
                            timeSlot: subj.timeSlot || '',
                            isPE: (subj.code || '').indexOf('PPE') === 0,
                            isMLC: (subj.code || '').indexOf('MLC') === 0
                        };
                    });
                    var peSubjects = mapped.filter(function(s) { return s.isPE; });
                    var others = mapped.filter(function(s) { return !s.isPE; });
                    if (peSubjects.length) {
                        var peItem = {
                            courseCode: 'PPE',
                            courseName: 'PPE',
                            sectionName: sectionLabel,
                            isPE: true,
                            credits: peSubjects[0].credits || 2,
                            peSubjects: PE_OPTIONS.map(function(name, i) { return { courseCode: 'PPE ' + (1101 + i), courseName: name }; })
                        };
                        return others.concat([peItem]);
                    }
                    return mapped;
                }
            }
            return [];
        }

        function isSectionEnrolled(courseName, sectionName, isPE, isMLC) {
            var usePrefix = isPE || isMLC;
            if (!usePrefix) {
                return window.alreadyEnrolled.some(function(e) {
                    if (e.course_name !== courseName) return false;
                    return e.section_name === sectionName || (e.section_name && e.section_name.indexOf(sectionName) === 0);
                }) || enrolledItems.some(function(e) {
                    if (e.courseName !== courseName) return false;
                    return e.sectionName === sectionName || (e.sectionName && e.sectionName.indexOf(sectionName) === 0);
                });
            }
            return window.alreadyEnrolled.some(function(e) { return e.course_name === courseName && (e.section_name === sectionName || (e.section_name && e.section_name.indexOf(sectionName) === 0)); }) ||
                enrolledItems.some(function(e) { return (isPE && courseName === 'PPE' ? (e.courseName === 'PPE' || (e.courseName && e.courseName.indexOf('PPE') === 0)) : e.courseName === courseName) && e.sectionName && e.sectionName.indexOf(sectionName) === 0; });
        }

        function toggleCategory(header, categoryType) {
            const category = header.closest('.course-category');
            const content = header.nextElementSibling;
            const caret = header.querySelector('.caret');
            
            // Close other categories
            if (currentSelectedCategory && currentSelectedCategory !== category) {
                const otherContent = currentSelectedCategory.querySelector('.category-content');
                const otherHeader = currentSelectedCategory.querySelector('.category-header');
                const otherCaret = otherHeader.querySelector('.caret');
                otherContent.classList.remove('expanded');
                otherHeader.classList.remove('active');
                otherCaret.classList.remove('expanded');
            }
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                header.classList.remove('active');
                caret.classList.remove('expanded');
                currentSelectedCategory = null;
            } else {
                content.classList.add('expanded');
                header.classList.add('active');
                caret.classList.add('expanded');
                currentSelectedCategory = category;
            }
        }

        function toggleSubcategory(header) {
            const content = header.nextElementSibling;
            const caret = header.querySelector('.caret');
            
            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                caret.classList.remove('expanded');
            } else {
                content.classList.add('expanded');
                caret.classList.add('expanded');
            }
        }

        function selectCourseWithCollege(element, courseName, collegeCourseId) {
            var hasOtherCollege = enrolledItems.some(function(e) {
                return e.collegeCourseId && Number(e.collegeCourseId) !== Number(collegeCourseId);
            });
            if (hasOtherCollege) {
                showRestrictionModal('You already have enrollments for another program. Save or remove them before enrolling in a different program.');
                return;
            }
            currentCollegeCourseId = collegeCourseId;
            selectCourse(element, courseName);
        }

        function selectCourse(element, courseName) {
            if (isK12Course(courseName) && enrolledItems.some(function(e) { return e.collegeCourseId; })) {
                showRestrictionModal('You have college program enrollments. Save or remove them before enrolling in K\u201312.');
                return;
            }
            document.querySelectorAll('.sub-subcategory').forEach(item => {
                item.classList.remove('selected');
            });
            element.classList.add('selected');
            currentSelectedCourse = courseName;
            currentCourseName = courseName;
            if (isK12Course(courseName)) currentCollegeCourseId = null;

            const sectionsContainer = document.getElementById('sectionsContainer');
            const sectionsTitle = document.getElementById('sectionsTitle');
            const sectionsList = document.getElementById('sectionsList');
            const saveWrap = document.getElementById('saveEnrollmentsWrap');

            sectionsTitle.textContent = courseName + ' - Available Sections';
            sectionsContainer.classList.add('visible');
            saveWrap.classList.toggle('visible', enrolledItems.length > 0);

            var sections = getSectionsForCourse(courseName);
            sectionsList.innerHTML = '';
            var slotCount = 40;
            sections.forEach(function(sec, idx) {
                var isK12 = sec.isK12;
                var isPE = sec.isPE;
                var isMLC = sec.isMLC;
                var courseNameForEnroll = isK12 ? courseName : sec.courseName;
                var sectionNameForEnroll = sec.sectionName;
                var isEnrolled = isK12
                    ? isSectionEnrolled(courseName, sectionNameForEnroll, false)
                    : isSectionEnrolled(courseNameForEnroll, sectionNameForEnroll, isPE, isMLC);
                var enrolledItemMatch = null;
                if (!isK12 && !isPE && !isMLC && isEnrolled) {
                    enrolledItemMatch = enrolledItems.find(function(e) {
                        if (e.courseName !== courseNameForEnroll) return false;
                        return (e.sectionName && e.sectionName.indexOf(sectionNameForEnroll) === 0) || (e.sectionName === sectionNameForEnroll);
                    });
                    if (!enrolledItemMatch) {
                        var fromServer = window.alreadyEnrolled.find(function(e) {
                            if (e.course_name !== courseNameForEnroll) return false;
                            return (e.section_name && e.section_name.indexOf(sectionNameForEnroll) === 0) || (e.section_name === sectionNameForEnroll);
                        });
                        if (fromServer) enrolledItemMatch = { courseName: fromServer.course_name, sectionName: fromServer.section_name, section_code: fromServer.section_code, timeSlot: fromServer.time_slot || '', days: fromServer.days || '' };
                    }
                }
                var headerText = isK12 ? sectionNameForEnroll : (isPE ? 'PPE' : (sec.courseCode + ' - ' + sec.courseName));
                var sectionItem = document.createElement('div');
                sectionItem.className = 'section-item';
                sectionItem.setAttribute('data-course-name', courseNameForEnroll);
                sectionItem.setAttribute('data-section-name', sectionNameForEnroll);
                sectionItem.setAttribute('data-is-pe', isPE ? '1' : '0');
                sectionItem.setAttribute('data-is-k12', isK12 ? '1' : '0');
                sectionItem.setAttribute('data-time-slot', sec.timeSlot || '');
                var enrolledCount = Math.floor(Math.random() * 36);
                var actionBtn = '';
                if (isK12) {
                    actionBtn = '<button type="button" class="btn-view' + (isEnrolled ? ' enrolled' : '') + '" data-course-name="' + escapeAttr(courseName) + '" data-section-name="' + escapeAttr(sectionNameForEnroll) + '" onclick="openK12Modal(this)">' + (isEnrolled ? 'Enrolled' : 'View') + '</button>';
                } else if (isPE) {
                    var peSubjectsJson = escapeAttr(JSON.stringify(sec.peSubjects || []));
                    var peUnits = (sec.credits != null && sec.credits !== '') ? escapeAttr(String(sec.credits)) : '2';
                    actionBtn = '<button type="button" class="btn-options' + (isEnrolled ? ' enrolled' : '') + '" data-course-name="' + escapeAttr(courseNameForEnroll) + '" data-section-name="' + escapeAttr(sectionNameForEnroll) + '" data-pe-subjects="' + peSubjectsJson + '" data-units="' + peUnits + '" onclick="openPeModal(this)">' + (isEnrolled ? 'Switch' : 'Options') + '</button>';
                } else if (isMLC) {
                    var mlcUnits = (sec.credits != null && sec.credits !== '') ? escapeAttr(String(sec.credits)) : '3';
                    actionBtn = '<button type="button" class="btn-options' + (isEnrolled ? ' enrolled' : '') + '" data-course-name="' + escapeAttr(courseNameForEnroll) + '" data-section-name="' + escapeAttr(sectionNameForEnroll) + '" data-units="' + mlcUnits + '" onclick="openMlcModal(this)">' + (isEnrolled ? 'Switch' : 'Options') + '</button>';
                } else {
                    if (isEnrolled && enrolledItemMatch) {
                        var matchSectionName = enrolledItemMatch.sectionName || sectionNameForEnroll;
                        var matchSectionCode = enrolledItemMatch.section_code || '';
                        var matchDays = enrolledItemMatch.days || '';
                        actionBtn = '<button type="button" class="btn-enroll enrolled" data-course-code="' + escapeAttr(sec.courseCode || '') + '" data-course-name="' + escapeAttr(courseNameForEnroll) + '" data-section-name="' + escapeAttr(matchSectionName) + '" data-section-code="' + escapeAttr(matchSectionCode) + '" data-time-slot="' + escapeAttr(enrolledItemMatch.timeSlot || '') + '" data-days="' + escapeAttr(matchDays) + '" data-units="' + (sec.credits != null && sec.credits !== '' ? escapeAttr(String(sec.credits)) : '') + '" onclick="toggleEnroll(this)">Remove</button>';
                    } else if (isEnrolled) {
                        actionBtn = '<button type="button" class="btn-enroll enrolled" data-course-code="' + escapeAttr(sec.courseCode || '') + '" data-course-name="' + escapeAttr(courseNameForEnroll) + '" data-section-name="' + escapeAttr(sectionNameForEnroll) + '" data-time-slot="' + escapeAttr(sec.timeSlot || '') + '" data-units="' + (sec.credits != null && sec.credits !== '' ? escapeAttr(String(sec.credits)) : '') + '" onclick="toggleEnroll(this)">Remove</button>';
                    } else {
                        actionBtn = '<button type="button" class="btn-enroll" data-course-code="' + escapeAttr(sec.courseCode || '') + '" data-course-name="' + escapeAttr(courseNameForEnroll) + '" data-section-name="' + escapeAttr(sectionNameForEnroll) + '" data-time-slot="' + escapeAttr(sec.timeSlot || '') + '" data-units="' + (sec.credits != null && sec.credits !== '' ? escapeAttr(String(sec.credits)) : '') + '" onclick="openEnrollModal(this)">Enroll</button>';
                    }
                }
                var detailsHtml = '';
                if (!isK12) {
                    if (sec.credits !== undefined && sec.credits > 0) {
                        detailsHtml = '<div class="section-details">' +
                            '<div class="section-detail-item"><span class="section-detail-label">Units</span><span class="section-detail-value">' + sec.credits + '</span></div>' +
                            '<div class="section-detail-item"><span class="section-detail-label">Status</span><span class="section-detail-value">Available</span></div>' +
                            '</div>';
                    } else {
                        detailsHtml = '<div class="section-details">' +
                            '<div class="section-detail-item"><span class="section-detail-label">Max Enrollees</span><span class="section-detail-value">40</span></div>' +
                            '<div class="section-detail-item"><span class="section-detail-label">Current Enrollees</span><span class="section-detail-value">' + enrolledCount + '</span></div>' +
                            '<div class="section-detail-item"><span class="section-detail-label">Status</span><span class="section-detail-value">Available</span></div>' +
                            '</div>';
                    }
                }
                sectionItem.innerHTML = '<div class="section-item-content">' +
                    '<div class="section-item-header">' +
                    '<span class="section-name">' + escapeHtml(headerText) + '</span>' +
                    (isK12 ? '' : '<span class="section-info">' + sectionNameForEnroll + '</span>') +
                    '</div>' +
                    detailsHtml +
                    '</div>' +
                    '<div class="section-item-actions">' + actionBtn + '</div>';
                sectionsList.appendChild(sectionItem);
            });
        }

        function escapeHtml(s) {
            var div = document.createElement('div');
            div.textContent = s;
            return div.innerHTML;
        }

        var peModalButton = null;
        function openPeModal(btn) {
            peModalButton = btn;
            var courseName = btn.getAttribute('data-course-name');
            var sectionName = btn.getAttribute('data-section-name');
            var peSubjectsRaw = btn.getAttribute('data-pe-subjects');
            var peSubjects = [];
            try { if (peSubjectsRaw) peSubjects = JSON.parse(peSubjectsRaw); } catch (err) {}
            var schedules = (window.peMlcSchedules && window.peMlcSchedules.pe) ? window.peMlcSchedules.pe : [];
            if (!peSubjects.length) peSubjects = PE_OPTIONS.map(function(name, i) { return { courseCode: 'PPE ' + (1101 + i), courseName: name }; });
            peSubjects = peSubjects.map(function(p, i) {
                var s = schedules[i] || {};
                return {
                    courseCode: p.courseCode || s.courseCode,
                    courseName: p.courseName || s.courseName,
                    section_code: s.section_code || ('PE-' + (i + 1)),
                    time_slot: s.time_slot || '',
                    days: s.days || ''
                };
            });
            document.getElementById('peModalTitle').textContent = 'Choose PE subject - ' + sectionName;
            var list = document.getElementById('peModalOptions');
            list.innerHTML = '';
            peSubjects.forEach(function(opt, idx) {
                var displayName = (opt.courseCode || '') + (opt.courseName ? ' - ' + opt.courseName : '');
                var sectionCode = opt.section_code || ('PE-' + (idx + 1));
                var fullSectionName = sectionName + ' - ' + sectionCode;
                var timeSlot = opt.time_slot || '';
                var days = opt.days || '';
                var scheduleText = days ? (days + ' ' + timeSlot) : timeSlot;
                var alreadyPicked = enrolledItems.some(function(e) { return e.courseName === displayName && e.sectionName && e.sectionName.indexOf(sectionName) === 0; });
                var hasConflict = timeSlot && hasTimeConflictGlobal(days, timeSlot);
                var conflictWithLabels = hasConflict ? getConflictWithGlobal(days, timeSlot) : [];
                var conflictText = conflictWithLabels.length ? ('Time conflict with: ' + conflictWithLabels.join(', ')) : '';
                var enrolledCount = 10 + (idx * 4);
                var statusText = enrolledCount < 40 ? 'Available' : 'Full';
                var card = document.createElement('div');
                card.className = 'enroll-modal-section-card' + (hasConflict ? ' enroll-modal-section-card--conflict' : '');
                var actionBtn = document.createElement('button');
                actionBtn.type = 'button';
                actionBtn.className = 'btn-enroll' + (alreadyPicked ? ' enrolled' : '');
                actionBtn.textContent = alreadyPicked ? 'Enrolled' : (hasConflict ? 'Conflict' : 'Select');
                if (alreadyPicked || hasConflict) actionBtn.disabled = true;
                var itemCourseName = displayName;
                var itemSectionName = fullSectionName;
                var itemTimeSlot = timeSlot;
                var itemDays = days;
                var itemSectionCode = sectionCode;
                actionBtn.onclick = function() {
                    if (alreadyPicked || hasConflict) return;
                    if (itemTimeSlot && hasTimeConflictGlobal(itemDays, itemTimeSlot)) return;
                    enrolledItems = enrolledItems.filter(function(e) {
                        return !((e.courseName === 'PPE' || (e.courseName && e.courseName.indexOf('PPE') === 0)) && e.sectionName && e.sectionName.indexOf(sectionName) === 0);
                    });
                    var item = { courseName: itemCourseName, sectionName: itemSectionName, section_code: itemSectionCode, timeSlot: itemTimeSlot, days: itemDays };
                    if (currentCollegeCourseId) item.collegeCourseId = currentCollegeCourseId;
                    var peUnits = btn.getAttribute('data-units');
                    if (peUnits) item.units = peUnits;
                    enrolledItems.push(item);
                    btn.textContent = 'Switch';
                    btn.classList.add('enrolled');
                    document.getElementById('saveEnrollmentsWrap').classList.toggle('visible', enrolledItems.length > 0);
                    document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
                    closePeModal();
                };
                card.innerHTML = '<div class="section-card-left">' +
                    '<div class="section-card-name">' + escapeHtml(displayName) + '</div>' +
                    '<div class="section-card-meta">' +
                    '<span><strong>Section:</strong> ' + escapeHtml(sectionCode) + '</span>' +
                    '<span><strong>Schedule:</strong> ' + escapeHtml(scheduleText || '—') + '</span>' +
                    '<span><strong>Enrolled:</strong> ' + enrolledCount + ' / 40</span>' +
                    '<span><strong>Status:</strong> ' + statusText + '</span>' +
                    (hasConflict && conflictText ? '<div class="conflict-warning" style="margin-top:0.5rem;">' + escapeHtml(conflictText) + '</div>' : '') +
                    '</div></div><div class="section-card-actions"></div>';
                card.querySelector('.section-card-actions').appendChild(actionBtn);
                list.appendChild(card);
            });
            document.getElementById('peModal').classList.add('visible');
        }
        function closePeModal() {
            document.getElementById('peModal').classList.remove('visible');
            peModalButton = null;
        }
        document.getElementById('peModal').addEventListener('click', function(e) {
            if (e.target === this) closePeModal();
        });

        function showRestrictionModal(message) {
            document.getElementById('restrictionModalMessage').textContent = message;
            document.getElementById('restrictionModal').classList.add('visible');
        }
        function closeRestrictionModal() {
            document.getElementById('restrictionModal').classList.remove('visible');
        }
        document.getElementById('restrictionModal').addEventListener('click', function(e) {
            if (e.target === this) closeRestrictionModal();
        });

        function getSemesterBase(sectionName) {
            if (!sectionName) return '';
            var i = sectionName.indexOf(' - Section ');
            if (i >= 0) return sectionName.substring(0, i).trim();
            var m = sectionName.match(/\s*-\s*[\dA-Z]+$/);
            return m ? sectionName.substring(0, sectionName.length - m[0].length).trim() : sectionName;
        }
        function getTakenSectionCodesForSemester(semesterBase) {
            var taken = [];
            enrolledItems.forEach(function(e) {
                if (getSemesterBase(e.sectionName) === semesterBase && e.section_code) taken.push(e.section_code);
            });
            (window.alreadyEnrolled || []).forEach(function(e) {
                if (getSemesterBase(e.section_name) === semesterBase && e.section_code) taken.push(e.section_code);
            });
            return taken;
        }
        function timeRangeToMinutes(str) {
            if (!str || str.indexOf(' - ') < 0) return null;
            var parts = str.split(' - ');
            var start = parseTimeToMinutes(parts[0].trim());
            var end = parseTimeToMinutes(parts[1].trim());
            return (start !== null && end !== null) ? [start, end] : null;
        }
        function parseTimeToMinutes(t) {
            var m = t.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
            if (!m) return null;
            var h = parseInt(m[1], 10); var min = parseInt(m[2], 10);
            if (m[3].toUpperCase() === 'PM' && h !== 12) h += 12;
            if (m[3].toUpperCase() === 'AM' && h === 12) h = 0;
            return h * 60 + min;
        }
        function timeRangesOverlap(range1, range2) {
            var a = timeRangeToMinutes(range1); var b = timeRangeToMinutes(range2);
            if (!a || !b) return range1 === range2;
            return a[0] < b[1] && b[0] < a[1];
        }
        function hasTimeConflict(semesterBase, timeRange) {
            if (!timeRange) return false;
            return enrolledItems.some(function(e) {
                if (getSemesterBase(e.sectionName) !== semesterBase) return false;
                return timeRangesOverlap(e.timeSlot || '', timeRange);
            });
        }
        function sameDaysForConflict(e, d) {
            var other = e.days;
            return (!d && !other) || (d && other && other === d);
        }
        function hasTimeConflictInSection(sectionCode, timeRange, days) {
            if (!timeRange || !sectionCode) return false;
            var conflict = enrolledItems.some(function(e) {
                return e.section_code === sectionCode && sameDaysForConflict(e, days) && timeRangesOverlap(e.timeSlot || '', timeRange);
            });
            if (conflict) return true;
            return (window.alreadyEnrolled || []).some(function(e) {
                return e.section_code === sectionCode && sameDaysForConflict(e, days) && timeRangesOverlap(e.time_slot || '', timeRange);
            });
        }
        function hasTimeConflictGlobal(days, timeRange) {
            if (!timeRange) return false;
            var conflict = enrolledItems.some(function(e) {
                return sameDaysForConflict(e, days) && timeRangesOverlap(e.timeSlot || '', timeRange);
            });
            if (conflict) return true;
            return (window.alreadyEnrolled || []).some(function(e) {
                return sameDaysForConflict(e, days) && timeRangesOverlap(e.time_slot || '', timeRange);
            });
        }
        function getConflictWithGlobal(days, timeRange) {
            if (!timeRange) return [];
            var labels = [];
            enrolledItems.forEach(function(e) {
                if (sameDaysForConflict(e, days) && timeRangesOverlap(e.timeSlot || '', timeRange)) {
                    var label = e.courseName || '';
                    if (e.section_code) label += ' (' + e.section_code + ')';
                    if (label) labels.push(label);
                }
            });
            (window.alreadyEnrolled || []).forEach(function(e) {
                if (sameDaysForConflict(e, days) && timeRangesOverlap(e.time_slot || '', timeRange)) {
                    var label = e.course_name || '';
                    if (e.section_code) label += ' (' + e.section_code + ')';
                    if (label) labels.push(label);
                }
            });
            return labels;
        }
        var enrollModalRef = { btn: null, courseName: null, sectionName: null, courseCode: null, units: null };
        function getSectionSubjectTime(sectionCode, courseCode) {
            if (!currentCollegeCourseId || !sectionCode || !courseCode || !window.sectionSubjectTimes) return '';
            var parsed = parseSemesterLabel(enrollModalRef.sectionName);
            if (!parsed) return '';
            var byYear = window.sectionSubjectTimes[String(currentCollegeCourseId)];
            if (!byYear) return '';
            var bySem = byYear[parsed.year];
            if (!bySem) return '';
            var bySection = bySem[parsed.semester];
            if (!bySection) return '';
            var byCourse = bySection[sectionCode];
            return (byCourse && byCourse[courseCode]) ? byCourse[courseCode] : '';
        }
        function openEnrollModal(btn) {
            var courseCode = btn.getAttribute('data-course-code') || '';
            var courseName = btn.getAttribute('data-course-name');
            var sectionName = btn.getAttribute('data-section-name');
            enrollModalRef.btn = btn;
            enrollModalRef.courseName = courseName;
            enrollModalRef.sectionName = sectionName;
            enrollModalRef.courseCode = courseCode;
            enrollModalRef.units = btn.getAttribute('data-units') || null;
            var headerText = courseCode ? (courseCode + ' - ' + courseName) : courseName;
            if (headerText.length > 35) headerText = headerText.substring(0, 32) + '...';
            document.getElementById('enrollModalTitle').textContent = headerText;
            var container = document.getElementById('enrollModalSections');
            container.innerHTML = '';
            var semesterBase = getSemesterBase(sectionName);
            var parsed = parseSemesterLabel(sectionName);
            var sections = [];
            if (currentCollegeCourseId && parsed && window.sectionsByCollege) {
                var byYear = window.sectionsByCollege[String(currentCollegeCourseId)];
                if (byYear) {
                    var bySem = byYear[parsed.year];
                    if (bySem) sections = bySem[parsed.semester] || [];
                }
            }
            if (sections.length === 0) {
                container.innerHTML = '<p class="section-card-meta" style="color:#6b7280;">No sections defined for this semester.</p>';
            }
            sections.forEach(function(sec) {
                var sectionCode = sec.section_code;
                var schedule = getSectionSubjectTime(sectionCode, courseCode);
                var timeRange = (schedule && typeof schedule === 'object' && schedule.time_slot) ? schedule.time_slot : (typeof schedule === 'string' ? schedule : '');
                var days = (schedule && typeof schedule === 'object' && schedule.days) ? schedule.days : '';
                var timeAndDaysText = days ? (escapeHtml(days) + ' &nbsp; ' + escapeHtml(timeRange || '—')) : (escapeHtml(timeRange) || '—');
                var fullSectionName = sectionName + ' - ' + sectionCode;
                var enrolledCount = Math.floor(Math.random() * 36);
                var available = enrolledCount < 40;
                var conflictInSection = hasTimeConflictInSection(sectionCode, timeRange, days);
                var conflictGlobal = hasTimeConflictGlobal(days, timeRange);
                var timeConflict = conflictInSection || conflictGlobal;
                var disabled = timeConflict || !available;
                var statusText = available ? 'Available' : 'Full';
                var conflictWithLabels = conflictGlobal ? getConflictWithGlobal(days, timeRange) : [];
                var conflictWarning = conflictGlobal && conflictWithLabels.length ? ('Time conflict with: ' + conflictWithLabels.join(', ')) : (conflictInSection ? 'Time conflict in this section' : '');
                var card = document.createElement('div');
                card.className = 'enroll-modal-section-card';
                if (conflictWarning) card.classList.add('enroll-modal-section-card--conflict');
                var enrollBtn = document.createElement('button');
                enrollBtn.type = 'button';
                enrollBtn.className = 'btn-enroll';
                if (disabled) enrollBtn.disabled = true;
                enrollBtn.textContent = 'Enroll';
                enrollBtn.setAttribute('data-section-code', sectionCode);
                enrollBtn.setAttribute('data-full-section', fullSectionName);
                enrollBtn.setAttribute('data-time-range', timeRange);
                enrollBtn.setAttribute('data-days', days);
                enrollBtn.onclick = function() { confirmEnrollInSection(this); };
                card.innerHTML = '<div class="section-card-left">' +
                    '<div class="section-card-name">' + escapeHtml(sectionCode) + '</div>' +
                    '<div class="section-card-meta">' +
                    '<span><strong>Schedule:</strong> ' + timeAndDaysText + '</span>' +
                    '<span><strong>Enrolled:</strong> ' + enrolledCount + ' / 40</span>' +
                    '<span><strong>Status:</strong> ' + statusText + '</span>' +
                    (conflictWarning ? '<div class="conflict-warning" style="margin-top:0.5rem;">' + escapeHtml(conflictWarning) + '</div>' : '') +
                    '</div></div>' +
                    '<div class="section-card-actions"></div>';
                card.querySelector('.section-card-actions').appendChild(enrollBtn);
                container.appendChild(card);
            });
            document.getElementById('enrollModalConflict').style.display = 'none';
            document.getElementById('enrollModal').classList.add('visible');
        }
        function closeEnrollModal() {
            document.getElementById('enrollModal').classList.remove('visible');
            enrollModalRef.btn = null;
            enrollModalRef.courseName = null;
            enrollModalRef.sectionName = null;
            enrollModalRef.courseCode = null;
            enrollModalRef.units = null;
        }
        function confirmEnrollInSection(clickedBtn) {
            var sectionCode = clickedBtn.getAttribute('data-section-code');
            var fullSectionName = clickedBtn.getAttribute('data-full-section');
            var timeRange = clickedBtn.getAttribute('data-time-range');
            var days = clickedBtn.getAttribute('data-days') || '';
            var ref = enrollModalRef;
            if (!ref.btn || !ref.courseName || !fullSectionName || !sectionCode) return;
            if (hasTimeConflictInSection(sectionCode, timeRange, days) || hasTimeConflictGlobal(days, timeRange)) return;
            var item = { courseName: ref.courseName, sectionName: fullSectionName, section_code: sectionCode, timeSlot: timeRange, days: days };
            if (currentCollegeCourseId) item.collegeCourseId = currentCollegeCourseId;
            if (ref.units) item.units = ref.units;
            enrolledItems.push(item);
            ref.btn.textContent = 'Remove';
            ref.btn.classList.add('enrolled');
            ref.btn.setAttribute('data-section-name', fullSectionName);
            ref.btn.setAttribute('data-section-code', sectionCode);
            ref.btn.setAttribute('data-time-slot', timeRange);
            ref.btn.setAttribute('data-days', days);
            ref.btn.onclick = function() { toggleEnroll(this); };
            document.getElementById('saveEnrollmentsWrap').classList.toggle('visible', enrolledItems.length > 0);
            document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
            closeEnrollModal();
        }
        document.getElementById('enrollModal').addEventListener('click', function(e) {
            if (e.target === this) closeEnrollModal();
        });

        var mlcModalButton = null;
        function openMlcModal(btn) {
            mlcModalButton = btn;
            var courseName = btn.getAttribute('data-course-name');
            var sectionName = btn.getAttribute('data-section-name');
            var mlcSchedules = (window.peMlcSchedules && window.peMlcSchedules.mlc) ? window.peMlcSchedules.mlc : [];
            document.getElementById('mlcModalTitle').textContent = 'Choose MLC track - ' + sectionName;
            var list = document.getElementById('mlcModalOptions');
            list.innerHTML = '';
            MLC_OPTIONS.forEach(function(opt, idx) {
                var s = mlcSchedules[idx] || {};
                var optName = s.option || opt;
                var fullSectionName = sectionName + ' - ' + optName;
                var alreadyPicked = enrolledItems.some(function(e) { return e.courseName === courseName && e.sectionName === fullSectionName; });
                var sectionCode = s.section_code || ('MLC-' + (idx + 1));
                var timeSlot = s.time_slot || '';
                var days = s.days || '';
                var scheduleText = days ? (days + ' ' + timeSlot) : timeSlot;
                var hasConflict = timeSlot && hasTimeConflictGlobal(days, timeSlot);
                var conflictWithLabels = hasConflict ? getConflictWithGlobal(days, timeSlot) : [];
                var conflictText = conflictWithLabels.length ? ('Time conflict with: ' + conflictWithLabels.join(', ')) : '';
                var enrolledCount = 12 + (idx * 5);
                var statusText = enrolledCount < 40 ? 'Available' : 'Full';
                var card = document.createElement('div');
                card.className = 'enroll-modal-section-card' + (hasConflict ? ' enroll-modal-section-card--conflict' : '');
                var actionBtn = document.createElement('button');
                actionBtn.type = 'button';
                actionBtn.className = 'btn-enroll' + (alreadyPicked ? ' enrolled' : '');
                actionBtn.textContent = alreadyPicked ? 'Enrolled' : (hasConflict ? 'Conflict' : 'Select');
                if (alreadyPicked || hasConflict) actionBtn.disabled = true;
                var itemSectionName = fullSectionName;
                var itemTimeSlot = timeSlot;
                var itemDays = days;
                var itemSectionCode = sectionCode;
                actionBtn.onclick = function() {
                    if (alreadyPicked || hasConflict) return;
                    if (itemTimeSlot && hasTimeConflictGlobal(itemDays, itemTimeSlot)) return;
                    enrolledItems = enrolledItems.filter(function(e) {
                        return !(e.courseName === courseName && e.sectionName && e.sectionName.indexOf(sectionName) === 0);
                    });
                    var item = { courseName: courseName, sectionName: itemSectionName, section_code: itemSectionCode, timeSlot: itemTimeSlot, days: itemDays };
                    if (currentCollegeCourseId) item.collegeCourseId = currentCollegeCourseId;
                    var mlcUnits = btn.getAttribute('data-units');
                    if (mlcUnits) item.units = mlcUnits;
                    enrolledItems.push(item);
                    btn.textContent = 'Switch';
                    btn.classList.add('enrolled');
                    document.getElementById('saveEnrollmentsWrap').classList.toggle('visible', enrolledItems.length > 0);
                    document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
                    closeMlcModal();
                };
                card.innerHTML = '<div class="section-card-left">' +
                    '<div class="section-card-name">' + escapeHtml(optName) + '</div>' +
                    '<div class="section-card-meta">' +
                    '<span><strong>Section:</strong> ' + escapeHtml(sectionCode) + '</span>' +
                    '<span><strong>Schedule:</strong> ' + escapeHtml(scheduleText || '—') + '</span>' +
                    '<span><strong>Enrolled:</strong> ' + enrolledCount + ' / 40</span>' +
                    '<span><strong>Status:</strong> ' + statusText + '</span>' +
                    (hasConflict && conflictText ? '<div class="conflict-warning" style="margin-top:0.5rem;">' + escapeHtml(conflictText) + '</div>' : '') +
                    '</div></div><div class="section-card-actions"></div>';
                card.querySelector('.section-card-actions').appendChild(actionBtn);
                list.appendChild(card);
            });
            document.getElementById('mlcModal').classList.add('visible');
        }
        function closeMlcModal() {
            document.getElementById('mlcModal').classList.remove('visible');
            mlcModalButton = null;
        }
        document.getElementById('mlcModal').addEventListener('click', function(e) {
            if (e.target === this) closeMlcModal();
        });

        function escapeAttr(s) {
            return s.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        }

        function toggleEnroll(btn) {
            if (!btn) return;
            const courseName = btn.getAttribute('data-course-name');
            const sectionName = btn.getAttribute('data-section-name');
            const sectionCode = btn.getAttribute('data-section-code');
            const idx = enrolledItems.findIndex(function(item) {
                if (item.courseName !== courseName) return false;
                if (sectionCode && item.section_code) return item.section_code === sectionCode;
                return item.sectionName === sectionName || (item.sectionName && item.sectionName.indexOf(sectionName) === 0);
            });
            if (idx >= 0) {
                enrolledItems.splice(idx, 1);
                btn.textContent = 'Enroll';
                btn.classList.remove('enrolled');
                btn.removeAttribute('data-section-code');
                btn.setAttribute('data-section-name', getSemesterBase(sectionName));
                btn.onclick = function() { openEnrollModal(this); };
            }
            var wrap = document.getElementById('saveEnrollmentsWrap');
            wrap.classList.toggle('visible', enrolledItems.length > 0);
            document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
        }

        var k12ModalRef = { btn: null, courseName: null, sectionName: null };
        function openK12Modal(btn) {
            var courseName = btn.getAttribute('data-course-name');
            var sectionName = btn.getAttribute('data-section-name');
            k12ModalRef.btn = btn;
            k12ModalRef.courseName = courseName;
            k12ModalRef.sectionName = sectionName;
            document.getElementById('k12ModalTitle').textContent = sectionName + ' — ' + courseName;
            var list = document.getElementById('k12ModalSubjects');
            list.innerHTML = '';
            K12_SUBJECTS.forEach(function(s) {
                var row = document.createElement('div');
                row.className = 'k12-subject-row';
                row.innerHTML = '<span class="k12-subject-name">' + escapeHtml(s.name) + '</span><span class="k12-subject-time">' + s.time + '</span>';
                list.appendChild(row);
            });
            var enrollBtn = document.getElementById('k12ModalEnrollBtn');
            var isEnrolled = enrolledItems.some(function(e) { return e.courseName === courseName && e.sectionName === sectionName; });
            enrollBtn.textContent = isEnrolled ? 'Remove' : 'Enroll';
            enrollBtn.classList.toggle('enrolled', isEnrolled);
            document.getElementById('k12Modal').classList.add('visible');
        }
        function closeK12Modal() {
            document.getElementById('k12Modal').classList.remove('visible');
            k12ModalRef.btn = null;
            k12ModalRef.courseName = null;
            k12ModalRef.sectionName = null;
        }
        function toggleK12Enroll() {
            var c = k12ModalRef.courseName;
            var s = k12ModalRef.sectionName;
            var btn = k12ModalRef.btn;
            var enrollBtn = document.getElementById('k12ModalEnrollBtn');
            var idx = enrolledItems.findIndex(function(e) { return e.courseName === c && e.sectionName === s; });
            if (idx >= 0) {
                enrolledItems.splice(idx, 1);
                enrollBtn.textContent = 'Enroll';
                enrollBtn.classList.remove('enrolled');
                if (btn) { btn.textContent = 'View'; btn.classList.remove('enrolled'); }
            } else {
                var item = { courseName: c, sectionName: s };
                if (currentCollegeCourseId) item.collegeCourseId = currentCollegeCourseId;
                enrolledItems.push(item);
                enrollBtn.textContent = 'Remove';
                enrollBtn.classList.add('enrolled');
                if (btn) { btn.textContent = 'Enrolled'; btn.classList.add('enrolled'); }
            }
            document.getElementById('saveEnrollmentsWrap').classList.toggle('visible', enrolledItems.length > 0);
            document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
        }
        document.getElementById('k12Modal').addEventListener('click', function(e) {
            if (e.target === this) closeK12Modal();
        });

        function clearAllEnrollments() {
            enrolledItems = [];
            document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
            document.getElementById('saveEnrollmentsWrap').classList.remove('visible');
            var el = document.querySelector('.sub-subcategory.selected');
            if (el && currentCourseName) {
                if (currentCollegeCourseId) {
                    selectCourseWithCollege(el, currentCourseName, currentCollegeCourseId);
                } else {
                    selectCourse(el, currentCourseName);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('saveEnrollmentsForm').addEventListener('submit', function() {
                document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
                document.getElementById('returnCourseNameInput').value = currentCourseName || '';
                document.getElementById('returnCollegeCourseIdInput').value = currentCollegeCourseId || '';
            });

            if (window.pendingEnrollmentsFromSession && window.pendingEnrollmentsFromSession.length > 0) {
                enrolledItems = window.pendingEnrollmentsFromSession.slice();
                document.getElementById('enrollItemsInput').value = JSON.stringify(enrolledItems);
                document.getElementById('saveEnrollmentsWrap').classList.add('visible');

                var returnCourseName = window.returnCourseName || '';
                var returnCollegeCourseId = window.returnCollegeCourseId || '';

                if (returnCollegeCourseId) {
                    var collegeCat = document.querySelector('.course-category[data-category="college"]');
                    if (collegeCat) {
                        var catContent = collegeCat.querySelector('.category-content');
                        if (catContent && !catContent.classList.contains('expanded')) {
                            collegeCat.querySelector('.category-header').click();
                        }
                    }
                }

                var tabEl = null;
                document.querySelectorAll('.sub-subcategory').forEach(function(el) {
                    if (el.getAttribute('data-course-name') === returnCourseName &&
                        String(el.getAttribute('data-college-course-id') || '') === String(returnCollegeCourseId || '')) {
                        tabEl = el;
                    }
                });

                if (tabEl && returnCollegeCourseId) {
                    var subcat = tabEl.closest('.subcategory');
                    if (subcat) {
                        var subContent = subcat.querySelector('.subcategory-content');
                        if (subContent && !subContent.classList.contains('expanded')) {
                            subcat.querySelector('.subcategory-header').click();
                        }
                    }
                }

                if (tabEl && returnCourseName) {
                    if (returnCollegeCourseId) {
                        selectCourseWithCollege(tabEl, returnCourseName, parseInt(returnCollegeCourseId, 10));
                    } else {
                        selectCourse(tabEl, returnCourseName);
                    }
                }
            }
        });
    </script>
</body>
</html>

