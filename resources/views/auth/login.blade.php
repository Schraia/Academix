<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Sign Up</title>
    @vite('resources/css/app.css')
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,rgb(250, 133, 133) 0%,rgb(158, 49, 49) 100%);
        }
        .auth-container {
            background: white;
            border-radius: 50px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #ef4444;
        }
        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg,rgb(234, 102, 102) 0%,rgb(162, 75, 75) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(234, 102, 102, 0.4);
        }
        .btn-google {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: border-color 0.3s;
        }
        .btn-google:hover {
            border-color: #db4437;
        }
        .switch-mode {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
        }
        .switch-mode button {
            background: none;
            border: none;
            color:rgb(234, 102, 102);
            cursor: pointer;
            font-weight: 600;
            text-decoration: underline;
        }
        .switch-mode button:hover {
            color:rgb(162, 75, 75);
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #9ca3af;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .divider span {
            padding: 0 1rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1 style="text-align: center; margin-bottom: 2rem; font-size: 2rem; color: #1f2937;" id="title">Login</h1>
        
        <form id="authForm" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div id="nameField" class="form-group" style="display: none;">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div id="passwordConfirmationField" class="form-group" style="display: none;">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

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

        <div class="divider">
            <span>OR</span>
        </div>

        <a href="{{ route('google.redirect') }}" class="btn-google">
            <svg width="20" height="20" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Continue with Google
        </a>

        <div class="switch-mode">
            <span id="switchText">Don't have an account? </span>
            <button type="button" id="switchBtn" onclick="switchMode()">Sign Up</button>
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

