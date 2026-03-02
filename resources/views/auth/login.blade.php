<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login / Sign Up</title>
  @vite('resources/css/app.css')

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    :root{
      --red1:#ff3b3b;
      --red2:#c71d1d;
      --red3:#ff5f5f;
      --ink:#0b0b0d;
      --ink2:#101014;
      --line: rgba(255,255,255,.18);
      --muted: rgba(255,255,255,.55);
      --text: rgba(255,255,255,.9);
      --focus: rgba(255,59,59,.95);
    }

    body{
      font-family: "Segoe UI", system-ui, -apple-system, sans-serif;
      height: 100svh;
      overflow: hidden;
      background: #000;
      color: var(--text);
    }

    /* ===== MAIN WRAPPER (controls swapping) ===== */
    .page{
      height: 100svh;
      display: flex;
      overflow: hidden;
      position: relative;

      /* dark to red look */
      background:
        radial-gradient(1200px 900px at 20% 50%, rgba(255,59,59,.45) 0%, rgba(255,59,59,0) 60%),
        radial-gradient(900px 700px at 85% 50%, rgba(0,0,0,.95) 0%, rgba(0,0,0,.55) 55%, rgba(0,0,0,.15) 100%),
        linear-gradient(90deg, #1a0b0b 0%, #0b0b0d 55%, #0b0b0d 100%);
    }

    /* swap panels in signup mode */
    .page.signup-mode{
      flex-direction: row-reverse;
      background:
        radial-gradient(1200px 900px at 80% 50%, rgba(255,59,59,.45) 0%, rgba(255,59,59,0) 60%),
        radial-gradient(900px 700px at 15% 50%, rgba(0,0,0,.95) 0%, rgba(0,0,0,.55) 55%, rgba(0,0,0,.15) 100%),
        linear-gradient(90deg, #0b0b0d 0%, #0b0b0d 45%, #1a0b0b 100%);
    }

    /* ===== PANELS ===== */
    .left-panel,
    .right-panel{
      flex: 1;
      min-width: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: clamp(18px, 3vw, 46px);
      position: relative;
      overflow: hidden;
    }

    /* ===== LEFT (red) ===== */
    .left-panel{
      color: #fff;
    }

    /* subtle line pattern */
    .left-panel::before{
      content:"";
      position:absolute;
      inset:-20%;
      background:
        repeating-linear-gradient(
          115deg,
          rgba(255,255,255,.14) 0px,
          rgba(255,255,255,.14) 1px,
          transparent 1px,
          transparent 12px
        );
      opacity: .18;
      transform: rotate(-10deg);
      pointer-events:none;
    }

    /* big wave shape (like your sample) */
    .left-panel::after{
      content:"";
      position:absolute;
      width: 120%;
      height: 120%;
      right: -55%;
      bottom: -65%;
      background: radial-gradient(closest-side at 40% 40%, rgba(0,0,0,.75) 0%, rgba(0,0,0,0) 70%);
      filter: blur(0.2px);
      pointer-events:none;
    }

    .logo{
      position: absolute;
      top: clamp(16px, 3vw, 40px);
      left: clamp(16px, 5vw, 80px);
      right: auto;
      width: clamp(84px, 9vw, 130px);
      transition: all .35s ease;
      z-index: 5;
    }
    .logo img{ width: 100%; display:block; filter: drop-shadow(0 8px 18px rgba(0,0,0,.25)); }

    .page.signup-mode .logo{
      left: auto;
      right: clamp(16px, 5vw, 80px);
    }

    .left-text{
      position: relative;
      z-index: 4;
      max-width: 560px;
      padding-left: clamp(0px, 2vw, 14px);
    }
    .left-text h1{
      font-size: clamp(2.4rem, 5.4vw, 5.6rem);
      line-height: 1.02;
      font-weight: 900;
      letter-spacing: -0.02em;
      text-shadow: 0 16px 35px rgba(0,0,0,.25);
    }

    /* ===== RIGHT (dark form) ===== */
    .right-panel{
  background: #0C0908;
}

    /* another wave on the form side for depth */
    .right-panel::after{
      content:"";
      position:absolute;
      width: 120%;
      height: 120%;
      left: -60%;
      bottom: -70%;
      background: radial-gradient(closest-side at 60% 40%, rgba(255,59,59,.16) 0%, rgba(255,59,59,0) 70%);
      pointer-events:none;
    }

    .auth-container{
      width: 100%;
      max-width: 560px;
      max-height: calc(100svh - (clamp(18px, 3vw, 46px) * 2));
      display: flex;
      flex-direction: column;
      justify-content: center;
      gap: clamp(10px, 1.6vh, 18px);
      position: relative;
      z-index: 3;
    }

    .auth-title{
  text-align:center;
  font-size: clamp(1.9rem, 2.3vw, 2.4rem);
  color: #FFFFFF;
  font-weight: 700;
}

    .form-group{
      position: relative;
      display:flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label{
      font-size: .95rem;
      color: rgba(255,255,255,.70);
    }

    .form-group .icon{
      position:absolute;
      left: 0;
      top: 62%;
      transform: translateY(-50%);
      width: 22px;
      height: 22px;
      opacity: .55;
    }
    .form-group .icon img{
      width:100%;
      height:100%;
      object-fit:contain;
      display:block;
      filter: grayscale(1) brightness(1.3);
      opacity: .85;
    }

    .form-group input{
      width:100%;
      background: transparent;
      border: none;
      border-bottom: 1px solid var(--line);
      padding: 14px 44px 14px 40px;
      font-size: 1.05rem;
      color: rgba(255,255,255,.88);
      outline: none;
    }
    .form-group input::placeholder{ color: rgba(255,255,255,.35); }
    .form-group input:focus{
      border-bottom: 2px solid var(--focus);
    }

    /* password toggle */
    .password-group{ position: relative; }
    .toggle-password{
      position:absolute;
      right: 6px;
      top: 62%;
      transform: translateY(-50%);
      cursor:pointer;
      font-size: 1.05rem;
      user-select:none;
      color: rgba(255,255,255,.55);
      padding: 6px 10px;
      border-radius: 10px;
    }
    .toggle-password:hover{
      color: rgba(255,255,255,.9);
      background: rgba(255,255,255,.06);
    }

    /* remember + forgot row like sample */
    .meta-row{
      display:flex;
      align-items:center;
      justify-content: space-between;
      gap: 14px;
      margin-top: 2px;
    }

    .remember-wrapper{
      display:flex;
      justify-content:flex-start;
      align-items:center;
    }
    .remember-label{
      display:flex;
      align-items:center;
      gap: 8px;
      font-size:.95rem;
      color: rgba(255,255,255,.70);
      cursor:pointer;
    }
    .remember-label input{
      width:auto;
      margin:0;
      accent-color: var(--red1);
    }

    .forgot-link{
      font-size: .95rem;
      color: rgba(255,255,255,.70);
      text-decoration: underline;
      text-underline-offset: 3px;
      opacity: .9;
    }
    .forgot-link:hover{ color: rgba(255,255,255,.92); }

    /* button like sample */
    .btn-primary{
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(90deg, rgba(255,59,59,1) 0%, rgba(199,29,29,1) 45%, rgba(255,95,95,1) 100%);
      color: #fff;
      font-weight: 700;
      font-size: 1.1rem;
      cursor:pointer;
      box-shadow: 0 18px 40px rgba(255,59,59,.12);
      transition: transform .12s ease, filter .12s ease;
    }
    .btn-primary:hover{ filter: brightness(1.03); }
    .btn-primary:active{ transform: translateY(1px); }

    .divider{
      text-align:center;
      color: rgba(255,255,255,.55);
      margin: 10px 0;
      letter-spacing: .04em;
    }

    /* google button (outlined, dark) */
    .google-btn{
      width:100%;
      padding: 14px 16px;
      border-radius: 999px;
      border: 1px solid rgba(255,255,255,.25);
      background: rgba(0,0,0,.35);
      text-decoration:none;
      display:flex;
      align-items:center;
      justify-content:center;
      gap: 12px;
      color: rgba(255,255,255,.90);
      transition: background .15s ease, border-color .15s ease;
    }
    .google-btn:hover{
      background: rgba(255,255,255,.05);
      border-color: rgba(255,255,255,.35);
    }
    .google-btn img{ width: 20px; display:block; }

    .switch-mode{
      text-align:center;
      margin-top: 12px;
      font-size: .98rem;
      color: rgba(255,255,255,.65);
    }
    .switch-mode button{
      background:none;
      border:none;
      color: rgba(255,255,255,.85);
      font-weight: 700;
      text-decoration: underline;
      text-underline-offset: 3px;
      cursor:pointer;
      margin-left: 6px;
    }
    .switch-mode button:hover{ color:#fff; }

    /* errors (if you have Laravel validation output) */
    .error-message{
      margin-top: 6px;
      font-size: .9rem;
      color: #ffb4b4;
    }

    /* Mobile: keep only form (like before) */
    @media (max-width: 1000px){
      .left-panel{ display:none; }
      .page, .page.signup-mode{
        background: #000;
        flex-direction: row;
      }
      .right-panel{
        width:100%;
        background: #000;
      }
    }
  </style>
</head>

<body>
  <div class="page" id="page">
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

        <h1 class="auth-title" id="title">Log in</h1>

        <form id="authForm" method="POST" action="{{ route('login') }}">
          @csrf

          <!-- NAME (SIGNUP ONLY) -->
          <div id="nameField" class="form-group" style="display:none;">
            <span class="icon"><img src="{{ asset('images/user.png') }}"></span>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Name">
            @error('name') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <!-- EMAIL -->
          <div class="form-group">
            <span class="icon"><img src="{{ asset('images/user.png') }}"></span>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email">
            @error('email') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <!-- PASSWORD -->
          <div class="form-group password-group">
            <span class="icon"><img src="{{ asset('images/lock.png') }}"></span>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="Password">
            <span class="toggle-password" id="togglePasswordBtn" onclick="togglePassword()">👁</span>
            @error('password') <div class="error-message">{{ $message }}</div> @enderror
          </div>

          <!-- CONFIRM PASSWORD (SIGNUP ONLY) -->
          <div id="passwordConfirmationField" class="form-group password-group" style="display:none;">
            <span class="icon"><img src="{{ asset('images/lock.png') }}"></span>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
            <span class="toggle-password" id="toggleConfirmBtn" onclick="toggleConfirm()">👁</span>
          </div>

          <!-- remember + forgot -->
          <div class="meta-row">
            <div id="rememberField" class="remember-wrapper">
              <label class="remember-label">
                <input type="checkbox" id="remember" name="remember">
                <span>Remember me</span>
              </label>
            </div>

            <!-- optional: link this to your route if you have it -->
            <a href="#" class="forgot-link" id="forgotLink">Forgot Password?</a>
          </div>

          <button type="submit" class="btn-primary" id="submitBtn">Log in</button>
        </form>

        @if (session('error'))
          <div class="error-message" style="text-align:center;">
            {{ session('error') }}
          </div>
        @endif

        <div class="divider">or</div>

        <a href="{{ route('google.redirect') }}" class="google-btn">
          <img src="{{ asset('images/google.png') }}">
          Log in with Google
        </a>

        <div class="switch-mode">
          <span id="switchText">Don't have an account?</span>
          <button type="button" id="switchBtn" onclick="switchMode()">Sign Up</button>
        </div>

      </div>
    </div>
  </div>

  <script>
    let isSignUp = false;

    function togglePassword() {
      const input = document.getElementById('password');
      const btn = document.getElementById('togglePasswordBtn');
      const hidden = input.type === 'password';
      input.type = hidden ? 'text' : 'password';
      btn.textContent = hidden ? '🙈' : '👁';
    }

    function toggleConfirm() {
      const input = document.getElementById('password_confirmation');
      const btn = document.getElementById('toggleConfirmBtn');
      const hidden = input.type === 'password';
      input.type = hidden ? 'text' : 'password';
      btn.textContent = hidden ? '🙈' : '👁';
    }

    function switchMode() {
      isSignUp = !isSignUp;

      const page = document.getElementById('page');
      const form = document.getElementById('authForm');
      const title = document.getElementById('title');
      const submitBtn = document.getElementById('submitBtn');
      const switchBtn = document.getElementById('switchBtn');
      const switchText = document.getElementById('switchText');
      const nameField = document.getElementById('nameField');
      const passwordConfirmationField = document.getElementById('passwordConfirmationField');
      const rememberField = document.getElementById('rememberField');
      const forgotLink = document.getElementById('forgotLink');

      const nameInput = document.getElementById('name');
      const passwordInput = document.getElementById('password');
      const passwordConfirmationInput = document.getElementById('password_confirmation');

      if (isSignUp) {
        page.classList.add('signup-mode');

        title.textContent = 'Sign Up';
        submitBtn.textContent = 'Sign Up';
        switchBtn.textContent = 'Log in';
        switchText.textContent = 'Already have an account?';

        form.action = '{{ route("register") }}';

        nameField.style.display = 'flex';
        passwordConfirmationField.style.display = 'flex';
        rememberField.style.display = 'flex';
        forgotLink.style.visibility = 'hidden'; // keep spacing but hide

        nameInput.required = true;
        passwordInput.required = true;
        passwordConfirmationInput.required = true;
      } else {
        page.classList.remove('signup-mode');

        title.textContent = 'Log in';
        submitBtn.textContent = 'Log in';
        switchBtn.textContent = 'Sign Up';
        switchText.textContent = "Don't have an account?";

        form.action = '{{ route("login") }}';

        nameField.style.display = 'none';
        passwordConfirmationField.style.display = 'none';
        rememberField.style.display = 'flex';
        forgotLink.style.visibility = 'visible';

        nameInput.required = false;
        passwordInput.required = true;
        passwordConfirmationInput.required = false;
      }
    }

    document.getElementById('authForm').addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      if (!email || !password) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return;
      }

      const submitBtn = document.getElementById('submitBtn');
      submitBtn.disabled = true;
      submitBtn.textContent = isSignUp ? 'Signing Up...' : 'Logging in...';
      submitBtn.style.opacity = '0.75';
      submitBtn.style.cursor = 'not-allowed';
    });
  </script>
</body>
</html>