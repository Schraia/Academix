<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Academix</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* ── Reset ──────────────────────────── */
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; }
        body { font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif; background:#f3f4f6; color:#111827; }
        .dashboard-container { display:flex; min-height:100vh; }

        /* ── Sidebar ──────────────────────────── */
        .sidebar { width:260px; height:100vh; position:sticky; top:0; flex-shrink:0; background:linear-gradient(180deg,#962121 0%,#991b1b 40%,#450a0a 100%); color:rgba(255,255,255,0.92); display:flex; flex-direction:column; box-shadow:8px 0 40px rgba(0,0,0,0.35); overflow:hidden; }
        .sidebar::after { content:""; position:absolute; inset:0; background:radial-gradient(circle at 20% 10%,rgba(255,255,255,0.05),transparent 40%),radial-gradient(circle at 80% 30%,rgba(255,255,255,0.04),transparent 40%); pointer-events:none; }
        .sidebar::before { content:''; position:absolute; top:0; right:0; width:3px; height:100%; background:linear-gradient(to bottom,rgba(255,255,255,0.5),transparent); opacity:0.3; }
        .sidebar-header { padding:2rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.1); display:flex; justify-content:center; align-items:center; }
        .sidebar-logo { max-width:140px; filter:drop-shadow(0 6px 12px rgba(0,0,0,0.4)); }
        .nav-menu { flex:1; min-height:0; overflow-y:auto; padding:1rem 0; }
        .nav-item { padding:0.9rem 1.75rem; cursor:pointer; display:flex; align-items:center; gap:0.9rem; font-weight:500; font-size:0.95rem; position:relative; transition:all 0.25s ease; text-decoration:none; color:inherit; }
        .nav-item:hover { background:rgba(255,255,255,0.08); padding-left:2.1rem; }
        .nav-item svg { width:19px; height:19px; opacity:0.85; transition:all 0.25s ease; }
        .nav-item:hover svg { opacity:1; transform:scale(1.15); }
        .nav-item.active { background:rgba(255,255,255,0.12); }
        .nav-item.active::before { content:""; position:absolute; left:0; top:0; height:100%; width:6px; background:linear-gradient(180deg,#ef4444,#ffffff); border-radius:0 6px 6px 0; }
        .nav-logout { margin-top:auto; padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.08); }
        .logout-btn { width:100%; padding:0.75rem; background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); border-radius:10px; cursor:pointer; font-size:1rem; font-weight:600; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:0.5rem; }
        .logout-btn:hover { background:#fff; color:#b91c1c; transform:translateY(-2px); }
        .logout-btn svg { width:20px; height:20px; }

        /* ── Main ──────────────────────────── */
        .main-content { flex:1; min-width:0; overflow:auto; padding:2rem 3rem; background:radial-gradient(circle at 10% 10%,rgba(185,28,28,0.18),transparent 50%),radial-gradient(circle at 90% 30%,rgba(220,38,38,0.15),transparent 50%),linear-gradient(180deg,#ffffff 0%,#f3f4f6 100%); }
        .page-header { margin-bottom:1.5rem; }
        .page-title { font-size:1.75rem; font-weight:700; color:#111827; margin-bottom:0.35rem; }
        .page-subtitle { font-size:0.9375rem; color:#6b7280; }

        /* ── Cards ──────────────────────────── */
        .card { background:#fff; border:1px solid rgba(229,231,235,0.95); border-radius:14px; box-shadow:0 4px 6px rgba(0,0,0,0.1); padding:1.25rem; margin-bottom:1.25rem; }
        .card-header { margin-bottom:0.9rem; display:flex; flex-direction:column; gap:0.2rem; }
        .card-title { font-size:0.95rem; font-weight:700; color:#111827; margin-bottom:0.05rem; }
        .card-subtitle { font-size:0.8rem; color:#6b7280; }
        .title-icon { font-size:0.95rem; color:#9ca3af; margin-right:0.4rem; vertical-align:-2px; }

        /* ── Metrics grid ──────────────────────────── */
        .metrics-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .metric-card { background:#fff; border:1px solid rgba(229,231,235,0.95); border-radius:12px; box-shadow:0 4px 6px rgba(0,0,0,0.08); padding:1rem 1.1rem; }
        .metric-label { display:flex; align-items:center; gap:0.4rem; font-size:0.8rem; font-weight:600; color:#6b7280; margin-bottom:0.35rem; }
        .metric-icon { font-size:0.9rem; color:#9ca3af; flex-shrink:0; }
        .metric-value { font-size:1.75rem; font-weight:700; color:#111827; margin-bottom:0.35rem; }
        .metric-meta { font-size:0.75rem; color:#6b7280; }
        .metric-meta.positive { color:#16a34a; font-weight:600; }
        .metric-meta.negative { color:#dc2626; font-weight:600; }
        .mini-bar { margin-top:0.5rem; height:6px; border-radius:3px; background:#e5e7eb; overflow:hidden; }
        .mini-bar > span { display:block; height:100%; background:linear-gradient(90deg,#dc2626,#991b1b); }
        .trend-grid { display:flex; align-items:flex-end; gap:6px; height:150px; margin-top:1.25rem; }
            .search-row { display:flex; align-items:center; gap:0.5rem; margin-top:0.5rem; }
            .search-input { width:100%; padding:0.4rem 0.6rem; border:1px solid #d1d5db; border-radius:8px; font-size:0.8rem; }
            .search-input:focus { outline:none; border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,0.12); }
            .search-count { font-size:0.72rem; color:#6b7280; white-space:nowrap; }

        .trend-col { flex:1; display:flex; flex-direction:column; align-items:center; gap:0.35rem; }
        .trend-count { font-size:0.68rem; color:#6b7280; line-height:1; }
        .trend-bar { width:100%; height:100px; background:#fee2e2; border-radius:6px; display:flex; align-items:flex-end; overflow:hidden; }
        .trend-bar span { display:block; width:100%; background:linear-gradient(180deg,#dc2626,#991b1b); border-radius:6px 6px 4px 4px; }
        .trend-label { font-size:0.7rem; color:#6b7280; }
        .stack-bar { display:flex; height:10px; border-radius:6px; overflow:hidden; background:#e5e7eb; margin-top:0.5rem; }
        .stack-pending { background:#f59e0b; }
        .stack-approved { background:#10b981; }
        .stack-rejected { background:#ef4444; }
        .stack-legend { display:flex; gap:0.8rem; font-size:0.72rem; color:#6b7280; margin-top:0.5rem; flex-wrap:wrap; }
        .legend-dot { width:8px; height:8px; border-radius:3px; display:inline-block; margin-right:0.3rem; }

        /* ── Workload chart ──────────────────────────── */
        .workload-card { position:relative; }
        .workload-kpi-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(210px,1fr)); gap:0.75rem; margin-bottom:1rem; }
        .workload-kpi { background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:0.8rem 0.9rem; }
        .workload-kpi-label { font-size:0.7rem; text-transform:uppercase; letter-spacing:0.04em; color:#6b7280; font-weight:600; }
        .workload-kpi-value { font-size:1.55rem; font-weight:700; color:#111827; margin-top:0.25rem; }
        .workload-kpi-meta { font-size:0.78rem; color:#4b5563; margin-top:0.25rem; }

        .workload-controls { display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:0.75rem; margin-bottom:0.75rem; }
        .filter-row { display:flex; flex-wrap:wrap; gap:0.6rem; }
        .filter-group { display:flex; flex-direction:column; gap:0.35rem; font-size:0.72rem; color:#6b7280; }
        .filter-select { min-width:160px; padding:0.4rem 0.6rem; border:1px solid #d1d5db; border-radius:8px; font-size:0.8rem; background:#fff; }
        .filter-select:focus { outline:none; border-color:#111827; box-shadow:0 0 0 3px rgba(17,24,39,0.12); }

        .workload-actions { display:flex; flex-wrap:wrap; align-items:center; gap:0.6rem; }
        .search-row { display:flex; align-items:center; gap:0.5rem; margin-top:0; }
        .toggle-group { display:flex; background:#f3f4f6; border-radius:999px; padding:0.2rem; gap:0.2rem; }
        .toggle-btn { border:none; background:transparent; padding:0.35rem 0.8rem; border-radius:999px; font-size:0.75rem; font-weight:600; color:#6b7280; cursor:pointer; }
        .toggle-btn.active { background:#111827; color:#fff; }

        .workload-legend { display:flex; gap:1rem; flex-wrap:wrap; font-size:0.75rem; color:#6b7280; margin-bottom:0.75rem; }
        .legend-chip { display:flex; align-items:center; gap:0.4rem; }
        .legend-swatch { width:10px; height:10px; border-radius:3px; display:inline-block; }

        .bar-chart { border-top:1px solid #f3f4f6; padding-top:0.75rem; }
        .bar-chart-header { display:grid; grid-template-columns:200px 1fr; font-size:0.72rem; color:#9ca3af; margin-bottom:0.35rem; }
        .bar-axis-scale { display:flex; justify-content:space-between; }
        .bar-tracks { position:relative; max-height:360px; overflow-y:auto; overflow-x:hidden; padding-right:0.5rem; }
        .bar-row { display:grid; grid-template-columns:200px 1fr; align-items:center; gap:0.75rem; padding:0.45rem 0; border-bottom:1px dashed #f3f4f6; }
        .bar-row:last-child { border-bottom:none; }
        .bar-row.is-clickable { cursor:pointer; }
        .bar-row.is-clickable:focus { outline:2px solid rgba(17,24,39,0.2); outline-offset:2px; border-radius:10px; }
        .bar-label { font-size:0.85rem; font-weight:600; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bar-track { position:relative; height:28px; background:#f3f4f6; border-radius:0; overflow:visible; }
        .bar-fill { position:absolute; inset:0 auto 0 0; border-radius:0; }
        .bar-value { position:absolute; top:50%; transform:translate(0,-50%); font-size:0.78rem; font-weight:700; color:#111827; white-space:nowrap; }
        .bar-threshold { position:absolute; top:0; bottom:0; width:2px; background:#111827; opacity:0.2; pointer-events:none; }
        .bar-threshold-label { position:absolute; top:0; transform:translate(-50%,-100%); font-size:0.7rem; color:#6b7280; white-space:nowrap; }

        .workload-tooltip { position:fixed; z-index:100; background:#111827; color:#fff; padding:0.6rem 0.75rem; border-radius:10px; font-size:0.75rem; max-width:260px; box-shadow:0 12px 25px rgba(17,24,39,0.2); display:none; }
        .workload-tooltip h4 { font-size:0.8rem; margin-bottom:0.35rem; }
        .workload-tooltip ul { margin:0; padding-left:1rem; }
        .workload-tooltip li { margin-bottom:0.2rem; }

        /* ── Status breakdown ──────────────────────────── */
        .status-breakdown { display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem; }
        .status-item { display:flex; align-items:center; gap:0.6rem; padding:0.65rem; background:#f9fafb; border-radius:8px; }
        .status-dot { width:12px; height:12px; border-radius:3px; flex-shrink:0; }
        .status-label { font-size:0.75rem; color:#6b7280; }
        .status-value { font-size:1.05rem; font-weight:700; color:#111827; }

        /* ── Stats grid ──────────────────────────── */
        .stats-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem; }
        .stat-box { background:#f9fafb; border-radius:8px; padding:0.9rem; text-align:center; }
        .stat-box-value { font-size:1.35rem; font-weight:700; color:#111827; }
        .stat-box-label { font-size:0.75rem; color:#6b7280; margin-top:0.2rem; }

        /* ── Activity ──────────────────────────── */
        .activity-table { width:100%; border-collapse:collapse; }
        .activity-table thead th { background:#f9fafb; padding:0.7rem 0.9rem; text-align:left; font-size:0.78rem; font-weight:700; color:#374151; border-bottom:1px solid #e5e7eb; }
        .activity-table tbody td { padding:0.7rem 0.9rem; border-bottom:1px solid #f3f4f6; font-size:0.85rem; }
        .activity-table tbody tr:last-child td { border-bottom:none; }
        .activity-badge { display:inline-block; padding:0.2rem 0.65rem; border-radius:6px; font-size:0.72rem; font-weight:700; }
        .badge-enrolled { background:#dbeafe; color:#1e40af; }
        .badge-pending { background:#fef3c7; color:#92400e; }
        .badge-approved { background:#dcfce7; color:#166534; }
        .badge-rejected { background:#fee2e2; color:#991b1b; }

        .empty-state { text-align:center; padding:1.5rem; color:#9ca3af; border:1px dashed #e5e7eb; border-radius:8px; background:#fafafa; }

        @media (max-width:1024px) { .main-content { padding:1.5rem 2rem; } }
        @media (max-width:900px) {
            .dashboard-container { flex-direction:column; }
            .sidebar { width:100%; height:auto; position:relative; }
            .nav-menu { display:flex; flex-wrap:wrap; gap:0.25rem; padding:0.75rem 0.75rem 1rem; }
            .nav-item { flex:1 1 160px; padding:0.7rem 1rem; }
        }
        @media (max-width:768px) {
            .main-content { padding:1rem 1.25rem; }
            .page-title { font-size:1.5rem; }
            .metrics-grid { grid-template-columns:1fr; }
            .status-breakdown { grid-template-columns:1fr; }
            .bar-chart-header, .bar-row { grid-template-columns:1fr; }
            .bar-label { margin-bottom:0.35rem; }
            .bar-track { height:24px; }
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header"><img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo"></div>
        <nav class="nav-menu">
            <a href="{{ route('dashboard') }}" class="nav-item active"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg><span>Dashboard</span></a>
            <a href="{{ route('settings.index') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg><span>Admin Panel</span></a>
        </nav>
        <div class="nav-logout">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="logout-btn"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg><span>Logout</span></button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="page-header">
            @php $u = Auth::user(); $displayName = trim($u->name ?: '') ?: 'Admin'; @endphp
            <div class="page-title">Hello, {{ $displayName }}</div>
            <div class="page-subtitle">System overview and admin metrics for your LMS.</div>
        </div>

        @php
            $enrollmentTrend = $enrollmentTrend ?? [];
            $totalStudents = $systemStats['totalStudents'] ?? 0;
            $totalInstructors = $systemStats['totalInstructors'] ?? 0;
            $totalCourses = $systemStats['totalCourses'] ?? 0;
            $totalSections = $systemStats['totalSections'] ?? 0;
            $totalUsers = $systemStats['totalUsers'] ?? 0;
            $totalEnrollments = $enrollmentStats['total'] ?? 0;
            $trend = $enrollmentStats['trend'] ?? 0;
            $pendingTotal = $pendingStats['total'] ?? 0;
            $pendingCount = $pendingStats['pending'] ?? 0;
            $approvedCount = $pendingStats['approved'] ?? 0;
            $rejectedCount = $pendingStats['rejected'] ?? 0;
            $avgLoad = $workloadStats['average'] ?? 0;
            $maxLoad = $workloadStats['max'] ?? 0;
            $trendMax = max(1, collect($enrollmentTrend)->max('count') ?? 0);
            $pendingPct = $pendingTotal > 0 ? round(($pendingCount / $pendingTotal) * 100) : 0;
            $approvedPct = $pendingTotal > 0 ? round(($approvedCount / $pendingTotal) * 100) : 0;
            $rejectedPct = max(0, 100 - $pendingPct - $approvedPct);
            $activityLimit = 10;
            $activityTotal = $activityTotal ?? count($recentActivity ?? []);
        @endphp

        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-people-fill metric-icon"></i>Total Students</div>
                <div class="metric-value">{{ number_format($totalStudents) }}</div>
                <div class="metric-meta">Registered learners in the system</div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-mortarboard-fill metric-icon"></i>Total Instructors</div>
                <div class="metric-value">{{ number_format($totalInstructors) }}</div>
                <div class="metric-meta">Active teaching accounts</div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-clipboard-data metric-icon"></i>Total Enrollments</div>
                <div class="metric-value">{{ number_format($totalEnrollments) }}</div>
                <div class="metric-meta {{ $trend >= 0 ? 'positive' : 'negative' }}">{{ $trend >= 0 ? '+' : '' }}{{ $trend }}% vs last week</div>
                <div class="mini-bar"><span style="width:{{ min(100, max(5, abs($trend))) }}%"></span></div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-hourglass-split metric-icon"></i>Pending Approvals</div>
                <div class="metric-value">{{ number_format($pendingCount) }}</div>
                <div class="metric-meta">Approved: {{ number_format($approvedCount) }} | Rejected: {{ number_format($rejectedCount) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-journal-bookmark-fill metric-icon"></i>Courses and Sections</div>
                <div class="metric-value">{{ number_format($totalCourses) }}</div>
                <div class="metric-meta">Sections: {{ number_format($totalSections) }}</div>
            </div>
            <div class="metric-card">
                <div class="metric-label"><i class="bi bi-bar-chart-line-fill metric-icon"></i>Avg Instructor Load</div>
                <div class="metric-value">{{ number_format($avgLoad, 1) }}</div>
                <div class="metric-meta">Max load: {{ number_format($maxLoad) }} course(s)</div>
            </div>
        </div>

        @php
            $workload = $instructorWorkload ?? [];
            $workloadData = [];

            foreach ($workload as $row) {
                $name = trim($row['name'] ?? '') ?: 'Unnamed';
                $courseCount = (int)($row['courseCount'] ?? 0);
                $workloadScore = isset($row['workloadScore']) ? (float)$row['workloadScore'] : $courseCount;
                $courseTitles = $row['courseTitles'] ?? [];
                if (!is_array($courseTitles)) { $courseTitles = [$courseTitles]; }
                $courseTitles = array_values(array_filter(array_map('strval', $courseTitles), function($title){ return $title !== ''; }));

                $semesterList = $row['semester'] ?? [];
                if (!is_array($semesterList)) { $semesterList = [$semesterList]; }
                $semesterList = array_values(array_filter(array_map('strval', $semesterList), function($val){ return $val !== ''; }));

                $departmentList = $row['department'] ?? [];
                if (!is_array($departmentList)) { $departmentList = [$departmentList]; }
                $departmentList = array_values(array_filter(array_map('strval', $departmentList), function($val){ return $val !== ''; }));

                $detailUrl = $row['detailUrl'] ?? (isset($row['id']) ? url('/admin/instructors/' . $row['id']) : null);

                $workloadData[] = [
                    'id' => $row['id'] ?? null,
                    'name' => $name,
                    'courseCount' => $courseCount,
                    'workloadScore' => $workloadScore,
                    'courseTitles' => $courseTitles,
                    'semesters' => $semesterList,
                    'departments' => $departmentList,
                    'detailUrl' => $detailUrl,
                ];
            }

            $sortedByCount = $workloadData;
            usort($sortedByCount, function($a, $b) {
                return ($b['courseCount'] ?? 0) <=> ($a['courseCount'] ?? 0);
            });

            $workloadCount = count($sortedByCount);
            $totalCourseCount = array_sum(array_map(function($row){ return (int)($row['courseCount'] ?? 0); }, $sortedByCount));
            $averageCourses = $workloadStats['average'] ?? ($workloadCount > 0 ? $totalCourseCount / $workloadCount : 0);
            $highestInstructor = $sortedByCount[0] ?? null;
            $lowestInstructor = $sortedByCount[$workloadCount - 1] ?? null;
            $workloadThreshold = $workloadThreshold ?? 6;
            $semesterOptions = is_array($semesterOptions ?? null) ? $semesterOptions : ['All'];
            $departmentOptions = is_array($departmentOptions ?? null) ? $departmentOptions : ['All'];
            if (!in_array('All', $semesterOptions, true)) { array_unshift($semesterOptions, 'All'); }
            if (!in_array('All', $departmentOptions, true)) { array_unshift($departmentOptions, 'All'); }
        @endphp
        <div class="card workload-card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-people title-icon"></i>Instructor Course Load</div>
                <div class="card-subtitle">Course assignments per instructor</div>
            </div>
            <div class="workload-kpi-grid">
                <div class="workload-kpi">
                    <div class="workload-kpi-label">Average courses</div>
                    <div class="workload-kpi-value">{{ number_format($averageCourses, 1) }}</div>
                    <div class="workload-kpi-meta">Across {{ number_format($workloadCount) }} instructors</div>
                </div>
                <div class="workload-kpi">
                    <div class="workload-kpi-label">Highest load</div>
                    <div class="workload-kpi-value">{{ number_format($highestInstructor['courseCount'] ?? 0) }}</div>
                    <div class="workload-kpi-meta">{{ $highestInstructor['name'] ?? 'No data yet' }}</div>
                </div>
                <div class="workload-kpi">
                    <div class="workload-kpi-label">Lowest load</div>
                    <div class="workload-kpi-value">{{ number_format($lowestInstructor['courseCount'] ?? 0) }}</div>
                    <div class="workload-kpi-meta">{{ $lowestInstructor['name'] ?? 'No data yet' }}</div>
                </div>
            </div>
            <div class="workload-controls">
                <div class="filter-row">
                    <label class="filter-group">
                        <span>Semester</span>
                        <select id="workloadSemester" class="filter-select">
                            @foreach($semesterOptions as $semester)
                                <option value="{{ $semester }}">{{ $semester }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="filter-group">
                        <span>Department</span>
                        <select id="workloadDepartment" class="filter-select">
                            @foreach($departmentOptions as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="workload-actions">
                    <div class="search-row">
                        <input id="workloadSearch" class="search-input" type="text" placeholder="Search instructor...">
                        <span id="workloadCount" class="search-count"></span>
                    </div>
                    <div class="toggle-group" role="tablist">
                        <button class="toggle-btn active" type="button" data-metric="courseCount" aria-pressed="true">Course Count</button>
                        <button class="toggle-btn" type="button" data-metric="workloadScore" aria-pressed="false">Workload Score</button>
                    </div>
                </div>
            </div>
            <div class="workload-legend">
                <span class="legend-chip"><span class="legend-swatch" style="background:#10b981;"></span>1-3 Underloaded</span>
                <span class="legend-chip"><span class="legend-swatch" style="background:#f59e0b;"></span>4-6 Balanced</span>
                <span class="legend-chip"><span class="legend-swatch" style="background:#dc2626;"></span>7+ Overloaded</span>
            </div>
            <div class="bar-chart">
                <div class="bar-chart-header">
                    <div>Instructor</div>
                    <div class="bar-axis-scale"><span>0</span><span id="workloadMaxLabel">0</span></div>
                </div>
                <div id="workloadChartTracks" class="bar-tracks"></div>
                <div id="workloadEmpty" class="empty-state" style="display:none;">No instructors assigned courses yet.</div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1rem; margin-bottom:1.25rem;">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-graph-up title-icon"></i>Enrollment Trend (7 days)</div>
                    <div class="card-subtitle">Daily enrolled students</div>
                </div>
                @if(!empty($enrollmentTrend))
                    <div class="trend-grid">
                        @foreach($enrollmentTrend as $day)
                            @php $h = $trendMax > 0 ? ($day['count'] / $trendMax) * 100 : 0; @endphp
                            <div class="trend-col">
                                <div class="trend-count">{{ $day['count'] }}</div>
                                <div class="trend-bar"><span style="height:{{ max(6, $h) }}%"></span></div>
                                <div class="trend-label">{{ $day['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">No enrollment data yet.</div>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-hourglass-split title-icon"></i>Pending Enrollments</div>
                    <div class="card-subtitle">Status breakdown</div>
                </div>
                <div class="status-breakdown">
                    <div class="status-item"><div class="status-dot" style="background:#f59e0b;"></div><div><div class="status-label">Pending</div><div class="status-value">{{ number_format($pendingCount) }}</div></div></div>
                    <div class="status-item"><div class="status-dot" style="background:#10b981;"></div><div><div class="status-label">Approved</div><div class="status-value">{{ number_format($approvedCount) }}</div></div></div>
                    <div class="status-item"><div class="status-dot" style="background:#ef4444;"></div><div><div class="status-label">Rejected</div><div class="status-value">{{ number_format($rejectedCount) }}</div></div></div>
                    <div class="status-item"><div class="status-dot" style="background:#6366f1;"></div><div><div class="status-label">Total Requests</div><div class="status-value">{{ number_format($pendingTotal) }}</div></div></div>
                </div>
                <div class="stack-bar">
                    <span class="stack-pending" style="width:{{ $pendingPct }}%"></span>
                    <span class="stack-approved" style="width:{{ $approvedPct }}%"></span>
                    <span class="stack-rejected" style="width:{{ $rejectedPct }}%"></span>
                </div>
                <div class="stack-legend">
                    <span><span class="legend-dot" style="background:#f59e0b;"></span>{{ $pendingPct }}% Pending</span>
                    <span><span class="legend-dot" style="background:#10b981;"></span>{{ $approvedPct }}% Approved</span>
                    <span><span class="legend-dot" style="background:#ef4444;"></span>{{ $rejectedPct }}% Rejected</span>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-grid-1x2 title-icon"></i>System Snapshot</div>
                    <div class="card-subtitle">At-a-glance totals</div>
                </div>
                <div class="stats-grid">
                    <div class="stat-box"><div class="stat-box-value">{{ number_format($totalStudents) }}</div><div class="stat-box-label">Students</div></div>
                    <div class="stat-box"><div class="stat-box-value">{{ number_format($totalInstructors) }}</div><div class="stat-box-label">Instructors</div></div>
                    <div class="stat-box"><div class="stat-box-value">{{ number_format($totalCourses) }}</div><div class="stat-box-label">Courses</div></div>
                    <div class="stat-box"><div class="stat-box-value">{{ number_format($totalUsers) }}</div><div class="stat-box-label">Total Users</div></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clock-history title-icon"></i>Recent Activity</div>
                <div class="card-subtitle">Showing latest {{ $activityLimit }} of {{ number_format($activityTotal) }} events</div>
            </div>
            @if(!empty($recentActivity))
                <div style="overflow-x:auto; max-height:280px; overflow-y:auto;">
                    <table class="activity-table">
                        <thead>
                            <tr><th>Student Email</th><th>Action</th><th>Status</th><th>Date and Time</th></tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($recentActivity, 0, $activityLimit) as $activity)
                                <tr>
                                    <td>{{ $activity['email'] ?? '-' }}</td>
                                    <td>{{ $activity['action'] ?? '-' }}</td>
                                    <td><span class="activity-badge badge-{{ $activity['statusClass'] ?? 'pending' }}">{{ $activity['status'] ?? 'Pending' }}</span></td>
                                    <td>{{ $activity['timestamp'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">No recent activity.</div>
            @endif
        </div>
    </div>
    <div id="workloadTooltip" class="workload-tooltip"></div>
    <script>
    (function(){
        var workloadData = @json($workloadData);
        var thresholdValue = {{ (int)$workloadThreshold }};
        var searchInput = document.getElementById('workloadSearch');
        var countLabel = document.getElementById('workloadCount');
        var semesterSelect = document.getElementById('workloadSemester');
        var departmentSelect = document.getElementById('workloadDepartment');
        var toggleButtons = document.querySelectorAll('.toggle-btn');
        var chartTracks = document.getElementById('workloadChartTracks');
        var emptyState = document.getElementById('workloadEmpty');
        var maxLabel = document.getElementById('workloadMaxLabel');
        var tooltip = document.getElementById('workloadTooltip');

        var activeMetric = 'courseCount';

        function escapeHtml(value){
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function normalizeList(value){
            if (!value) return [];
            if (Array.isArray(value)) return value.map(function(v){ return String(v).toLowerCase(); });
            return String(value).split(',').map(function(v){ return v.trim().toLowerCase(); }).filter(Boolean);
        }

        function getMetricValue(item){
            return activeMetric === 'workloadScore' ? Number(item.workloadScore || 0) : Number(item.courseCount || 0);
        }

        function formatMetricValue(value){
            if (activeMetric === 'workloadScore') {
                return value % 1 === 0 ? value : value.toFixed(1);
            }
            return Math.round(value);
        }

        function getWorkloadColor(courseCount){
            if (courseCount >= 7) return '#dc2626';
            if (courseCount >= 4) return '#f59e0b';
            if (courseCount >= 1) return '#10b981';
            return '#9ca3af';
        }

        function updateCount(visible){
            if (countLabel) countLabel.textContent = visible + ' shown';
        }

        function positionThresholdLine(maxValue){
            if (!chartTracks) return;
            var thresholdLine = chartTracks.querySelector('.bar-threshold');
            var thresholdLabel = chartTracks.querySelector('.bar-threshold-label');
            var firstTrack = chartTracks.querySelector('.bar-track');
            if (!thresholdLine || !thresholdLabel || !firstTrack) return;

            var trackRect = firstTrack.getBoundingClientRect();
            var tracksRect = chartTracks.getBoundingClientRect();
            var pct = maxValue > 0 ? Math.min(thresholdValue / maxValue, 1) : 0;
            var left = trackRect.left - tracksRect.left + (trackRect.width * pct);
            thresholdLine.style.left = left + 'px';
            thresholdLabel.style.left = left + 'px';
        }

        function positionValue(track, value, pct){
            if (!track || !value) return;
            var trackWidth = track.clientWidth;
            var valueWidth = value.offsetWidth;
            var leftPx = (trackWidth * (pct / 100)) + 8;
            if (leftPx + valueWidth > trackWidth) {
                leftPx = Math.max(0, trackWidth - valueWidth);
            }
            value.style.left = leftPx + 'px';
        }

        function positionAllValues(){
            if (!chartTracks) return;
            chartTracks.querySelectorAll('.bar-value').forEach(function(value){
                var track = value.parentElement;
                var pct = Number(value.dataset.pct || 0);
                positionValue(track, value, pct);
            });
        }

        function showTooltip(event, item){
            if (!tooltip) return;
            var courseTitles = Array.isArray(item.courseTitles) ? item.courseTitles : [];
            var list = courseTitles.slice(0, 6).map(function(title){ return '<li>' + escapeHtml(title) + '</li>'; }).join('');
            var extraCount = courseTitles.length - 6;
            if (!list) list = '<li>No courses assigned</li>';
            if (extraCount > 0) list += '<li>+' + extraCount + ' more</li>';

            tooltip.innerHTML =
                '<h4>' + escapeHtml(item.name) + '</h4>' +
                '<div>Total courses: <strong>' + escapeHtml(item.courseCount) + '</strong></div>' +
                '<div style="margin-top:0.35rem;">Courses:</div>' +
                '<ul>' + list + '</ul>';
            tooltip.style.display = 'block';
            moveTooltip(event);
        }

        function moveTooltip(event){
            if (!tooltip) return;
            var padding = 14;
            var x = event.clientX + padding;
            var y = event.clientY + padding;
            var rect = tooltip.getBoundingClientRect();
            if (x + rect.width > window.innerWidth) x = event.clientX - rect.width - padding;
            if (y + rect.height > window.innerHeight) y = event.clientY - rect.height - padding;
            tooltip.style.left = x + 'px';
            tooltip.style.top = y + 'px';
        }

        function hideTooltip(){
            if (tooltip) tooltip.style.display = 'none';
        }

        function buildRow(item, maxValue){
            var row = document.createElement('div');
            var label = document.createElement('div');
            var track = document.createElement('div');
            var fill = document.createElement('div');
            var value = document.createElement('div');
            var metricValue = getMetricValue(item);
            var pct = maxValue > 0 ? Math.round((metricValue / maxValue) * 1000) / 10 : 0;

            row.className = 'bar-row';
            if (item.detailUrl) row.classList.add('is-clickable');
            row.setAttribute('tabindex', item.detailUrl ? '0' : '-1');
            row.setAttribute('role', item.detailUrl ? 'button' : 'group');
            row.dataset.detailUrl = item.detailUrl || '';

            label.className = 'bar-label';
            label.textContent = item.name;

            track.className = 'bar-track';
            fill.className = 'bar-fill';
            fill.style.width = pct + '%';
            fill.style.background = getWorkloadColor(item.courseCount || 0);
            value.className = 'bar-value';
            value.textContent = formatMetricValue(metricValue);
            var displayPct = Math.min(100, Math.max(0, pct));
            value.dataset.pct = displayPct;

            track.appendChild(fill);
            track.appendChild(value);
            row.appendChild(label);
            row.appendChild(track);

            requestAnimationFrame(function(){
                positionValue(track, value, displayPct);
            });

            row.addEventListener('mouseenter', function(e){ showTooltip(e, item); });
            row.addEventListener('mousemove', moveTooltip);
            row.addEventListener('mouseleave', hideTooltip);
            row.addEventListener('click', function(){ if (item.detailUrl) window.location.href = item.detailUrl; });
            row.addEventListener('keydown', function(e){
                if (item.detailUrl && (e.key === 'Enter' || e.key === ' ')) {
                    e.preventDefault();
                    window.location.href = item.detailUrl;
                }
            });
            return row;
        }

        function render(){
            if (!chartTracks) return;
            var query = (searchInput && searchInput.value ? searchInput.value : '').toLowerCase().trim();
            var semester = semesterSelect ? semesterSelect.value.toLowerCase() : 'all';
            var department = departmentSelect ? departmentSelect.value.toLowerCase() : 'all';

            var filtered = workloadData.filter(function(item){
                var nameMatch = item.name.toLowerCase().indexOf(query) !== -1;
                var semesters = normalizeList(item.semesters);
                var departments = normalizeList(item.departments);
                var semesterMatch = semester === 'all' || semesters.indexOf(semester) !== -1 || semesters.length === 0;
                var departmentMatch = department === 'all' || departments.indexOf(department) !== -1 || departments.length === 0;
                return nameMatch && semesterMatch && departmentMatch;
            });

            filtered.sort(function(a, b){
                return getMetricValue(b) - getMetricValue(a);
            });

            var maxValue = 0;
            var showThreshold = activeMetric === 'courseCount';
            filtered.forEach(function(item){
                maxValue = Math.max(maxValue, getMetricValue(item));
            });
            maxValue = Math.max(maxValue, showThreshold ? thresholdValue : 0, 1);

            chartTracks.innerHTML = '';
            var thresholdLine = document.createElement('div');
            thresholdLine.className = 'bar-threshold';
            var thresholdLabel = document.createElement('div');
            thresholdLabel.className = 'bar-threshold-label';
            thresholdLabel.textContent = 'Max rec: ' + thresholdValue;
            chartTracks.appendChild(thresholdLine);
            chartTracks.appendChild(thresholdLabel);

            if (!showThreshold) {
                thresholdLine.style.display = 'none';
                thresholdLabel.style.display = 'none';
            }

            filtered.forEach(function(item){
                chartTracks.appendChild(buildRow(item, maxValue));
            });

            if (maxLabel) maxLabel.textContent = formatMetricValue(maxValue);
            if (emptyState) emptyState.style.display = filtered.length ? 'none' : 'block';
            updateCount(filtered.length);
            if (showThreshold) {
                setTimeout(function(){ positionThresholdLine(maxValue); }, 0);
            }
            setTimeout(positionAllValues, 0);
        }

        toggleButtons.forEach(function(btn){
            btn.addEventListener('click', function(){
                toggleButtons.forEach(function(inner){
                    inner.classList.remove('active');
                    inner.setAttribute('aria-pressed', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-pressed', 'true');
                activeMetric = btn.getAttribute('data-metric') || 'courseCount';
                render();
            });
        });

        if (searchInput) searchInput.addEventListener('input', render);
        if (semesterSelect) semesterSelect.addEventListener('change', render);
        if (departmentSelect) departmentSelect.addEventListener('change', render);
        window.addEventListener('resize', function(){
            if (activeMetric !== 'courseCount') return;
            positionThresholdLine(maxLabel ? Number(maxLabel.textContent) : 1);
            positionAllValues();
        });

        render();
    })();
    </script>
</div>
</body>
</html>