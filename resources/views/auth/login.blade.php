<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login / Sign Up</title>
    @vite('resources/css/app.css')

    <style>
                body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            background: linear-gradient(
                90deg,
                #ff5f5f 0%,
                #ff7b7b 40%,
                #f3f3f3 55%,
                #f3f3f3 100%
            );
            overflow-x: hidden;
        }

        /* LEFT SIDE */
        .left-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        .logo {
            position: absolute;
            top: 40px;
            left: 80px;
            width: 130px;
        }

        .logo img {
            width: 100%;
        }

        .left-panel::before {
            content: "";
            position: absolute;
            right: -200px;
            top: 50%;
            transform: translateY(-50%);
            width: 800px;
            height: 800px;
            background: repeating-radial-gradient(
                circle,
                rgba(255,255,255,0.25) 0px,
                rgba(255,255,255,0.25) 1px,
                transparent 1px,
                transparent 15px
            );
            opacity: 0.3;
        }

        .left-text {
            position: relative;
            color: white;
        }

        .left-text h1 {
            font-size: clamp(2.5rem, 6vw, 5.5rem);
            line-height: 1.1;
            font-weight: 800;
            margin: 0;
        }

        /* RIGHT SIDE */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .auth-container {
            width: 100%;
            max-width: 500px;
        }

        .auth-title {
            text-align: center;
            font-size: 2.5rem;
            color: #ff5f5f;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-group .icon {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
        }

        .form-group .icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-group input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #aaa;
            padding: 14px 0 14px 45px;
            background: transparent;
            font-size: 1.1rem;
        }

        .form-group input:focus {
            outline: none;
            border-bottom: 2px solid #ff5f5f;
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #ffdede, #ff5f5f);
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .divider {
            text-align: center;
            margin: 2.5rem 0;
            color: #999;
        }

        .google-btn {
            width: 100%;
            padding: 14px;
            border-radius: 30px;
            border: 1px solid #aaa;
            background: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
            color: #333;
        }

        .google-btn img {
            width: 20px;
        }

        .switch-mode {
            text-align: center;
            margin-top: 2.5rem;
            font-size: 1rem;
        }

        .switch-mode button {
            background: none;
            border: none;
            color: #ff5f5f;
            font-weight: bold;
            text-decoration: underline;
            cursor: pointer;
        }

        @media(max-width: 1000px) {
            .left-panel { display: none; }
            body {
                min-height: 100svh; 
                background: #f3f3f3; }
        }
    </style>
</head>
<body>

<!-- LEFT PANEL -->
<div class="left-panel">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
    </div>
    <div class="left-text">
        <h1>Learn,<br>Grow &<br>Succeed.</h1>
    </div>
</div>

<!-- RIGHT PANEL -->
<div class="right-panel">
    <div class="auth-container">

        <h1 class="auth-title" id="title">Login</h1>

        <form id="authForm" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- NAME (SIGNUP ONLY) -->
           <div id="nameField" class="form-group" style="display: none;">
                <span class="icon">
                    <img src="{{ asset('images/user.png') }}">
                </span>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>


            <!-- EMAIL -->
        
            <div class="form-group">
                <span class="icon">
                    <img src="{{ asset('images/user.png') }}">
                </span>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <span class="icon">
                    <img src="{{ asset('images/lock.png') }}">
                </span>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD (SIGNUP ONLY) -->
            <div id="passwordConfirmationField" class="form-group" style="display:none;">
                <span class="icon">
                    <img src="{{ asset('images/lock.png') }}">
                </span>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <!-- REMEMBER -->
             <div id="rememberField" class="form-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                <input type="checkbox" id="remember" name="remember" style="width: auto; margin-right: 0.5rem;">
                <label for="remember" style="margin-bottom: 0; font-weight: normal;">Remember me</label>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">Login</button>
        </form>

        @if (session('error'))
            <div class="error-message" style="margin-top: 1rem; text-align: center; padding: 0.75rem; background: #fee2e2; border-radius: 8px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="divider">or</div>

         <a href="{{ route('google.redirect') }}" class="google-btn">
            <img src="{{ asset('images/google.png') }}">
            Continue with Google
        </a>

        <div class="switch-mode">
            <span id="switchText">Don't have an account? </span>
            <button type="button" id="switchBtn" onclick="switchMode()">Sign Up</button>
        </div>

    </div>
</div>


    <script>
        let isSignUp = false;

        function switchMode() {
            isSignUp = !isSignUp;
            const form = document.getElementById('authForm');
            const title = document.getElementById('title');
            const submitBtn = document.getElementById('submitBtn');
            const switchBtn = document.getElementById('switchBtn');
            const switchText = document.getElementById('switchText');
            const nameField = document.getElementById('nameField');
            const passwordConfirmationField = document.getElementById('passwordConfirmationField');
            const rememberField = document.getElementById('rememberField');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            const nameInput = document.getElementById('name');
            
            if (isSignUp) {
                title.textContent = 'Sign Up';
                submitBtn.textContent = 'Sign Up';
                switchBtn.textContent = 'Login';
                switchText.textContent = 'Already have an account? ';
                form.action = '{{ route("register") }}';
                nameField.style.display = 'block';
                passwordConfirmationField.style.display = 'block';
                rememberField.style.display = 'none';
                nameInput.required = true;
                passwordInput.required = true;
                passwordConfirmationInput.required = true;
            } else {
                title.textContent = 'Login';
                submitBtn.textContent = 'Login';
                switchBtn.textContent = 'Sign Up';
                switchText.textContent = "Don't have an account? ";
                form.action = '{{ route("login") }}';
                nameField.style.display = 'none';
                passwordConfirmationField.style.display = 'none';
                rememberField.style.display = 'flex';
                nameInput.required = false;
                passwordInput.required = true;
                passwordConfirmationInput.required = false;
            }
        }

        // Ensure form submission works
        document.getElementById('authForm').addEventListener('submit', function(e) {
            // Validate form before submission
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all required fields.');
                return false;
            }
            
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = isSignUp ? 'Signing Up...' : 'Logging in...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
            
            // Form will submit naturally
            return true;
        });

        // Also handle button click directly as fallback
        document.getElementById('submitBtn').addEventListener('click', function(e) {
            const form = document.getElementById('authForm');
            if (form.checkValidity()) {
                form.requestSubmit();
            } else {
                form.reportValidity();
            }
        });
    </script>
</body>
</html>