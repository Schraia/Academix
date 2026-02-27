<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px; min-height: 100vh; flex-shrink: 0;
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
            color: white; display: flex; flex-direction: column;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-header { padding: 2rem 1.5rem; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-header h2 { font-size: 1.5rem; font-weight: 700; }
        .nav-menu { flex: 1; min-height: 0; overflow-y: auto; padding: 1rem 0; }
        .nav-item {
            padding: 1rem 1.5rem; display: flex; align-items: center; gap: 0.75rem;
            color: inherit; text-decoration: none; transition: background-color 0.3s; cursor: pointer;
        }
        .nav-item:hover { background-color: rgba(255, 255, 255, 0.1); }
        .nav-item.active { background-color: rgba(255, 255, 255, 0.2); }
        .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-logout { margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255, 255, 255, 0.1); background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%); }
        .logout-btn {
            width: 100%; padding: 1rem 1.5rem; background: transparent;
            color: white; border: none; cursor: pointer; font-size: 1rem;
            display: flex; align-items: center; gap: 0.75rem; text-align: left;
        }
        .logout-btn:hover { background-color: rgba(255, 255, 255, 0.1); }
        .logout-btn svg { width: 20px; height: 20px; }
        .main-content { flex: 1; padding: 2rem 3rem; display: flex; flex-direction: column; min-width: 0; }
        .main-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem; }
        .course-title-row { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 0.25rem; }
        .course-title-row .course-title { margin-bottom: 0; }
        .btn-edit { display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; color: #6b7280; text-decoration: none; border: 1px solid #e5e7eb; background: white; transition: color 0.2s, border-color 0.2s; }
        .btn-edit:hover { color: #dc2626; border-color: #dc2626; }
        .btn-edit svg { width: 18px; height: 18px; }
        .profile-card {
            border: 2px solid #dc2626; border-radius: 12px; background: white;
            padding: 1rem 1.25rem; display: flex; align-items: center; gap: 1rem; min-width: 220px;
        }
        .profile-card .avatar { width: 48px; height: 48px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #6b7280; font-size: 1.5rem; }
        .profile-card .info { flex: 1; }
        .profile-card .name { font-weight: 700; color: #1f2937; }
        .profile-card .status { font-size: 0.875rem; color: #6b7280; }
        .profile-card .btn-profile { margin-top: 0.5rem; padding: 0.35rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
        .profile-card .btn-profile:hover { background: #b91c1c; }
        .hamburger { padding: 0.5rem; color: #374151; cursor: pointer; }
        .course-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 0.25rem; }
        .course-code { font-size: 1rem; color: #6b7280; margin-bottom: 1rem; }
        .course-banner {
            width: 100%; max-height: 280px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%);
        }
        .course-banner-placeholder {
            width: 100%; height: 220px; border-radius: 12px; margin-bottom: 1rem;
            background: linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.8); font-size: 1rem;
        }
        .course-description { font-size: 0.9375rem; color: #374151; line-height: 1.6; }
        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start; }
        .content-grid .course-left { min-width: 0; }
        .content-grid .activity-column { margin-top: 0; }
        .course-left .course-description { margin-bottom: 0; }
        @media (max-width: 900px) { .content-grid { grid-template-columns: 1fr; } .content-grid .activity-column { margin-top: 0; } }
        .activity-box {
            background: white; border-radius: 12px; border: 1px solid #e5e7eb;
            padding: 1.25rem; margin-bottom: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .activity-box h3 { font-size: 0.9375rem; font-weight: 700; color: #1f2937; margin-bottom: 0.75rem; }
        .activity-box .preview { font-size: 0.875rem; color: #4b5563; line-height: 1.5; }
        .activity-box .preview + .preview { margin-top: 0.5rem; }
        .discussion-reply { min-width: 0; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; padding-left: 1.25rem; font-weight: normal; font-size: 0.875rem; color: #4b5563; line-height: 1.5; }
        .activity-box a.link-go { color: #dc2626; font-weight: 600; font-size: 0.875rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem; margin-top: 0.5rem; }
        .activity-box a.link-go:hover { text-decoration: underline; }
        .activity-box .badge { font-size: 0.75rem; color: #dc2626; margin-left: 0.25rem; }
        .file-list { list-style: none; }
        .file-list li { font-size: 0.875rem; color: #4b5563; padding: 0.35rem 0; border-bottom: 1px solid #f3f4f6; }
        .file-list li:last-child { border-bottom: none; }
        .bottom-links { display: flex; flex-wrap: wrap; gap: 1.5rem; margin-top: 1.5rem; }
        .bottom-link {
            background: white; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 1rem 1.25rem; text-decoration: none; color: #1f2937; font-weight: 600; font-size: 0.9375rem;
            display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .bottom-link:hover { border-color: #dc2626; color: #dc2626; }
        .bottom-link .badge { font-size: 0.75rem; font-weight: 600; color: #dc2626; }
        .upload-dropdown-wrap { position: relative; }
        .upload-btn { padding: 0.5rem 1rem; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9375rem; }
        .upload-btn:hover { background: #b91c1c; }
        .upload-dropdown { position: absolute; top: 100%; left: 0; margin-top: 0.25rem; background: white; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); min-width: 160px; z-index: 50; overflow: hidden; }
        .upload-dropdown a { display: block; padding: 0.75rem 1rem; color: #1f2937; text-decoration: none; font-size: 0.9375rem; border-bottom: 1px solid #f3f4f6; }
        .upload-dropdown a:last-child { border-bottom: none; }
        .upload-dropdown a:hover { background: #f9fafb; color: #dc2626; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Academix</h2>
            </div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('courses.index') }}" class="nav-item active">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    <span>Courses</span>
                </a>
                <a href="#" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg><span>Profile</span></a>
                <a href="{{ route('enroll') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg><span>Enroll Online</span></a>
                <a href="#" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg><span>Certificates</span></a>
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
            <a href="{{ route('courses.index') }}" class="back-to-courses" style="display: inline-block; margin-bottom: 1rem; color: #dc2626; font-weight: 600; font-size: 0.9375rem; text-decoration: none;">← Back to Courses</a>
            <div class="main-top">
                <div class="profile-card">
                    <div class="avatar">👤</div>
                    <div class="info">
                        <div class="name">{{ Auth::user()->name }}</div>
                        <div class="status">{{ $enrollment->section_name ?? 'Student' }}</div>
                        <a href="#" class="btn-profile">Profile</a>
                    </div>
                </div>
                <div class="hamburger" aria-label="Menu">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </div>
            </div>

            <div class="content-grid">
                <div class="course-left">
                    <div class="course-title-row">
                        <h1 class="course-title">{{ $course->title }}</h1>
                        @if(Auth::user()->isInstructor())
                        <a href="{{ route('courses.edit', $course) }}" class="btn-edit" title="Edit course (banner &amp; description)">
                            <svg fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                        </a>
                        <div class="upload-dropdown-wrap">
                            <button type="button" class="upload-btn" id="uploadBtn" aria-haspopup="true" aria-expanded="false">Upload ▾</button>
                            <div class="upload-dropdown" id="uploadDropdown" hidden>
                                <a href="{{ route('courses.upload.lessons', $course) }}">Lessons</a>
                                <a href="{{ route('courses.upload.announcements', $course) }}">Announcements</a>
                                <a href="{{ route('courses.upload.grades', $course) }}">Grades</a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <p class="course-code">{{ $course->code ?? $course->id }}</p>

                    @if($course->banner_path)
                    <img src="{{ asset('storage/' . $course->banner_path) }}" alt="" class="course-banner">
                    @else
                    <div class="course-banner-placeholder">
                        {{ $course->title }} — Learning materials
                    </div>
                    @endif

                    <p class="course-description">{{ $course->description ?? 'No description set.' }}</p>
                </div>
                <div class="activity-column" style="margin-top: 5.5rem;">
                    <div class="activity-box">
                        <h3>Ongoing Discussion:</h3>
                        @forelse($ongoingThreads as $thread)
                            <div class="preview" style="font-weight: 700;">{{ Str::limit($thread->title, 60) }}</div>
                            @php $latestMessage = $thread->messages->sortByDesc('created_at')->first(); @endphp
                            @if($latestMessage)
                                <div class="discussion-reply" title="{{ $latestMessage->user->name ?? 'User' }}: {{ $latestMessage->content }}">{{ $latestMessage->user->name ?? 'User' }}: {{ $latestMessage->content }}</div>
                            @elseif($thread->content)
                                @php $author = $thread->user->name ?? 'User'; @endphp
                                <div class="discussion-reply" title="{{ $author }}: {{ $thread->content }}">{{ $author }}: {{ $thread->content }}</div>
                            @endif
                        @empty
                            <div class="preview">No discussions yet.</div>
                        @endforelse
                        <a href="{{ route('courses.discussions', $course) }}" class="link-go">Go to Discussions → @if($discussionCount > 0)<span class="badge">{{ $discussionCount }} New Notifications</span>@endif</a>
                    </div>
                    <div class="activity-box">
                        <h3>Last lesson uploaded:</h3>
                        @if($lastLesson)
                            <div class="preview" style="font-weight: 700;">{{ $lastLesson->title }}</div>
                            <div class="preview" style="padding-left: 1.25rem; font-weight: normal;">{{ Str::limit($lastLesson->description, 100) ?: '—' }}</div>
                            @if($lastLesson->attachment_path)
                                @php $ext = pathinfo($lastLesson->attachment_path, PATHINFO_EXTENSION); $filename = $lastLesson->title . ($ext ? '.' . $ext : ''); @endphp
                                <div class="preview" style="padding-left: 1.25rem; font-weight: normal; margin-top: 0.25rem;"><a href="{{ route('courses.lessons.preview', [$course, $lastLesson]) }}" style="color: #dc2626; text-decoration: none;">{{ $filename }}</a></div>
                            @endif
                            <div class="preview" style="padding-left: 1.25rem; font-size: 0.8125rem; color: #6b7280; margin-top: 0.25rem;">{{ ($lastLesson->published_at ?? $lastLesson->updated_at)->format('M j, Y g:i A') }}</div>
                        @else
                            <div class="preview">No lessons yet.</div>
                        @endif
                    </div>
                    <div class="activity-box">
                        <h3>Recently opened files:</h3>
                        <ul class="file-list" style="padding-left: 1.25rem;">
                            @forelse($recentLessons as $lesson)
                                <li style="padding-left: 0;">
                                    @if($lesson->attachment_path)
                                        @php $ext = pathinfo($lesson->attachment_path, PATHINFO_EXTENSION); @endphp
                                        <a href="{{ route('courses.lessons.preview', [$course, $lesson]) }}" style="color: #dc2626; text-decoration: none;">{{ $course->code ?? 'Course' }} {{ $lesson->title }}{{ $ext ? '.' . $ext : '' }}</a>
                                    @else
                                        {{ $course->code ?? 'Course' }} {{ $lesson->title }}
                                    @endif
                                </li>
                            @empty
                                <li style="padding-left: 0;">No recent files.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bottom-links" style="margin-top: 2rem;">
                <a href="{{ route('courses.announcements', $course) }}" class="bottom-link">Go to Announcements → @if($announcementCount > 0)<span class="badge">{{ $announcementCount }} New Notifications</span>@endif</a>
                <a href="{{ route('courses.lessons', $course) }}" class="bottom-link">Go to Lessons → @if($lessonUploadCount > 0)<span class="badge">{{ $lessonUploadCount }} New Upload</span>@endif</a>
                <a href="{{ route('courses.grades', $course) }}" class="bottom-link">Go to Grades → @if($newGradedCount > 0)<span class="badge">{{ $newGradedCount }} New Graded</span>@endif</a>
            </div>
        </div>
    </div>
    @if(Auth::user()->isInstructor())
    <script>
        document.getElementById('uploadBtn').addEventListener('click', function() {
            var d = document.getElementById('uploadDropdown');
            d.hidden = !d.hidden;
        });
        document.addEventListener('click', function(e) {
            if (!document.getElementById('uploadBtn').contains(e.target) && !document.getElementById('uploadDropdown').contains(e.target)) {
                document.getElementById('uploadDropdown').hidden = true;
            }
        });
    </script>
    @endif
</body>
</html>
