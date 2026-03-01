<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Academix</title>
    @vite('resources/css/app.css')
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f3f4f6; }
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
        .main-content { flex: 1; padding: 2rem 3rem; background:
        radial-gradient(circle at 10% 10%, rgba(185,28,28,0.18), transparent 50%),
        radial-gradient(circle at 90% 30%, rgba(220,38,38,0.15), transparent 50%),
        linear-gradient(180deg, #ffffff 0%, #f3f4f6 100%);}
        .page-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
        .card table { width: 100%; border-collapse: collapse; }
        .card th, .card td { padding: 1rem 1.25rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .card th { background: #f9fafb; font-weight: 600; color: #374151; }
        .card tr:last-child td { border-bottom: none; }
        .role-form { display: flex; align-items: center; gap: 0.5rem; }
        .role-form select { padding: 0.35rem 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; }
        .role-form button { padding: 0.35rem 0.75rem; background: #dc2626; color: white; border: none; border-radius: 6px; font-size: 0.875rem; cursor: pointer; }
        .role-form button:hover { background: #b91c1c; }
        .badge-role { font-size: 0.75rem; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 600; }
        .badge-student { background: #e5e7eb; color: #374151; }
        .badge-instructor { background: #dbeafe; color: #1d4ed8; }
        .badge-admin { background: #fce7f3; color: #be185d; }
        .alert-success { background: #dcfce7; color: #166534; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        body.modal-open { overflow: hidden; }
        .modal { display: none; position: fixed; z-index: 10; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); animation: fadeIn 0.3s; }
        .modal-content { background-color: #fefefe; position: relative; top: 50%; left: 50%; transform: translate(-50%, -50%); border: 1px solid #888; width: 90%; max-width: 1200px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: slideIn 0.3s; }
        @keyframes fadeIn { from {opacity: 0} to {opacity: 1} }
        @keyframes slideIn { from {transform: translate(-50%, -60%)} to {transform: translate(-50%, -50%)} }
        .modal-header { padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .modal-header h2 { font-size: 1.25rem; font-weight: 600; }
        .modal-header .close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
        .modal-header .close:hover, .modal-header .close:focus { color: black; }
        .modal-body { padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .modal-body h3 { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; border-bottom: 1px solid #ddd; padding-bottom: 0.5rem; }
        #search-courses { width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 6px; margin-bottom: 1rem; }
        .courses-list { height: 300px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.5rem; }
        .course-item { display: flex; align-items: center; padding: 0.5rem; border-radius: 6px; }
        .course-item:hover { background-color: #f9fafb; }
        .course-item label { flex-grow: 1; cursor: pointer; display: block; }
        .course-item .course-code { font-weight: 600; color: #dc2626; margin-right: 8px; }
        .course-item .course-credits { font-size: 0.8rem; color: #6b7280; margin-left: 8px; background: #e5e7eb; padding: 2px 6px; border-radius: 4px; }
        .modal-footer { padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .footer-info { font-size: 0.9rem; color: #374151; }
        .footer-buttons { display: flex; gap: 0.5rem; }
        .footer-buttons button { padding: 0.6rem 1.2rem; border-radius: 6px; font-size: 0.875rem; cursor: pointer; border: none; font-weight: 600; }
        .btn-primary { background: #dc2626; color: white; }
        .btn-primary:hover { background: #b91c1c; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-secondary:hover { background: #d1d5db; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header"><img src="{{ asset('images/logo.png') }}" alt="Academix Logo" class="sidebar-logo"></div>
            <nav class="nav-menu">
                <a href="{{ route('dashboard') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg><span>Dashboard</span></a>
                <a href="{{ route('courses.index') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg><span>Courses</span></a>
                <a href="{{ route('profile.show') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg><span>Profile</span></a>
                <a href="{{ route('certificates.index') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg><span>Certificates</span></a>
                <a href="{{ route('settings.index') }}" class="nav-item active"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/></svg><span>Settings</span></a>
                <a href="{{ route('enroll') }}" class="nav-item"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg><span>Enroll Online</span></a>
            </nav>
            <div class="nav-logout">
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="btn-primarymit" class="logout-btn"><svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/></svg><span>Logout</span></button>
                </form>
            </div>
        </div>
        <div class="main-content">
            <h1 class="page-title">Settings</h1>
            <p style="color: #6b7280; margin-bottom: 1rem;">Set user roles. You can make an existing user an <strong>Instructor</strong> or <strong>Admin</strong>.</p>
            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-danger">{{ session('error') }}</div>
            @endif
            <div class="card">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current role</th>
                            <th>Set role</th>
                            <th>Assigned Courses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @if($u->role === 'admin')
                                        <span class="badge-role badge-admin">Admin</span>
                                    @elseif($u->role === 'instructor')
                                        <span class="badge-role badge-instructor">Instructor</span>
                                    @else
                                        <span class="badge-role badge-student">Student</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('settings.updateRole') }}" method="POST" class="role-form">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <select name="role">
                                            <option value="student" {{ $u->role === 'student' ? 'selected' : '' }}>Student</option>
                                            <option value="instructor" {{ $u->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                            <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                                <td>
                                    @if($u->role === 'instructor')
                                        <button onclick="openModal({{ $u->id }}, '{{ $u->name }}', {{ $u->courses->toJson() }})" type="button" class="role-form button">Assign Courses</button>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="assign-modal" class="modal">
        <div class="modal-content">
            <form id="assign-form" action="{{ route('settings.assignCourses') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="modal-user-id">
                <div class="modal-header">
                    <h2 id="modal-title">Assign Courses</h2>
                    <span class="close" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <div id="assigned-courses-container">
                        <h3>Assigned Courses</h3>
                        <div id="assigned-courses-list" class="courses-list"></div>
                    </div>
                    <div id="available-courses-container">
                        <h3>Available Courses</h3>
                        <input type="text" id="search-courses" placeholder="Search for courses...">
                        <div id="available-courses-list" class="courses-list"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="footer-info">
                        Total Credits: <span id="total-credits">0</span>
                    </div>
                    <div class="footer-buttons">
                        <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const allCourses = @json($courses);
        let currentAssignedCourses = [];

        function openModal(userId, userName, assignedCourses) {
            document.body.classList.add('modal-open');
            document.getElementById('modal-user-id').value = userId;
            document.getElementById('modal-title').innerText = 'Assign Courses to ' + userName;
            
            currentAssignedCourses = assignedCourses.map(c => c.id);

            renderCourseLists();
            
            document.getElementById('assign-modal').style.display = 'block';
        }

        function closeModal() {
            document.body.classList.remove('modal-open');
            document.getElementById('assign-modal').style.display = 'none';
        }

        function renderCourseLists(searchTerm = '') {
            const assignedList = document.getElementById('assigned-courses-list');
            const availableList = document.getElementById('available-courses-list');
            
            assignedList.innerHTML = '';
            availableList.innerHTML = '';
            let totalCredits = 0;

            const filteredCourses = allCourses.filter(course => 
                course.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                course.code.toLowerCase().includes(searchTerm.toLowerCase())
            );

            allCourses.forEach(course => {
                const isAssigned = currentAssignedCourses.includes(course.id);
                if (isAssigned) {
                    totalCredits += parseFloat(course.credits);
                    assignedList.innerHTML += createCourseItem(course, true);
                }
            });

            filteredCourses.forEach(course => {
                const isAssigned = currentAssignedCourses.includes(course.id);
                if (!isAssigned) {
                    availableList.innerHTML += createCourseItem(course, false);
                }
            });

            document.getElementById('total-credits').innerText = totalCredits.toFixed(2);
        }

        function createCourseItem(course, isAssigned) {
            const isChecked = currentAssignedCourses.includes(course.id);
            const hiddenInputs = isChecked ? `<input type="hidden" name="courses[]" value="${course.id}">` : '';

            return `
                <div class="course-item">
                    <input type="checkbox" id="course-${course.id}" value="${course.id}" ${isChecked ? 'checked' : ''} onchange="toggleCourse(${course.id})">
                    <label for="course-${course.id}">
                        <span class="course-code">(${course.code})</span>
                        ${course.title}
                        <span class="course-credits">${course.credits} units</span>
                    </label>
                    ${hiddenInputs}
                </div>
            `;
        }

        function toggleCourse(courseId) {
            const index = currentAssignedCourses.indexOf(courseId);
            if (index > -1) {
                currentAssignedCourses.splice(index, 1);
            } else {
                currentAssignedCourses.push(courseId);
            }
            renderCourseLists(document.getElementById('search-courses').value);
        }

        document.getElementById('search-courses').addEventListener('input', (e) => {
            renderCourseLists(e.target.value);
        });

        window.onclick = function(event) {
            const modal = document.getElementById('assign-modal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
