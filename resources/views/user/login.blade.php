@extends('layouts.user-auth-app')
@section('content')
    <section class="login-fullscreen-section">
        <div class="login-card">
            <h1 class="login-title">LOGIN</h1>
            <div class="login-decorative-divider">
                <img src="{{ asset('assets/images/login_card_innericon.png')}}" alt="La Pavone Emblem"
                    class="login-decorative-icon" onerror="this.style.display='none'">
            </div>
            <div class="mb-4 mt-0" id="guest-login-container">
                <button type="button" id="btn-choice-guest" class="login-submit-btn w-100 " style="margin:0;">
                    Guest Login
                </button>
            </div>
            <div class="align-items-center my-3 text-muted" id="guest-or-separator"
                style="font-family:'Outfit',sans-serif;font-size:12px;letter-spacing:0.5px;display:flex !important">
                <hr style="flex-grow:1;border-color:rgba(35,75,70,0.15);margin:0 10px;">
                <span>OR</span>
                <hr style="flex-grow:1;border-color:rgba(35,75,70,0.15);margin:0 10px;">
            </div>
            <form class="login-form" id="login-form">
                <!-- Step 1: Enter Mobile or Email -->
                <div id="step-input" class="form-step">
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="login-input">Mobile Number or Email</label>
                        <input type="text" id="login-input" class="form-control" placeholder="Enter Mobile Number or Email"
                            required autocomplete="username">
                    </div>
                    <button type="button" id="btn-continue" class="login-submit-btn w-100 m-0">Continue</button>
                    <div class="d-flex align-items-center my-3 text-muted"
                        style="font-family:'Outfit',sans-serif;font-size:12px;letter-spacing:0.5px;">
                        <hr style="flex-grow:1;border-color:rgba(35,75,70,0.15);margin:0 10px;">
                        <span>OR</span>
                        <hr style="flex-grow:1;border-color:rgba(35,75,70,0.15);margin:0 10px;">
                    </div>
                    <button type="button" class="google-login-btn w-100 d-flex align-items-center justify-content-center"
                        onclick="window.location.href='{{ route('auth.google') }}'">
                        <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Logo">
                        Login with Google
                    </button>
                </div>
                <!-- Step 2a: Enter OTP (mobile path) -->
                <div id="step-otp" class="form-step" style="display:none;">
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#555;margin-bottom:12px;">
                        OTP sent to <strong id="display-mobile"></strong>
                    </p>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="otp">Enter OTP</label>
                        <input type="text" id="otp" class="form-control" placeholder="Enter OTP" required maxlength="6"
                            inputmode="numeric">
                    </div>
                    <button type="button" id="btn-verify-otp" class="login-submit-btn w-100 m-0">Verify &amp; Login</button>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" id="btn-back-from-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="#" id="btn-resend-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#1F5552;text-decoration:underline;">
                            Resend OTP
                        </a>
                    </div>
                </div>
                <!-- Step 2b: Enter Password (email path) -->
                <div id="step-password" class="form-step" style="display:none;">
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#555;margin-bottom:12px;">
                        Logging in as <strong id="display-email"></strong>
                    </p>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="password">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter Password" required
                            autocomplete="current-password">
                    </div>
                    <button type="button" id="btn-login-password" class="login-submit-btn w-100 m-0">Login</button>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" id="btn-back-from-password"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="#" id="btn-forgot-password"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#1F5552;text-decoration:underline;">
                            Forgot Password?
                        </a>
                    </div>
                </div>
                <!-- Step 3: Forgot Password – Enter Email -->
                <div id="step-forgot-email" class="form-step" style="display:none;">
                    <h5 style="font-family:'Cinzel',serif;color:#1F5552;margin-bottom:8px;font-size:15px;">Reset Password
                    </h5>
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#555;margin-bottom:14px;">
                        Enter your registered email. We'll send an OTP to reset your password.
                    </p>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="forgot-email">Email Address</label>
                        <input type="email" id="forgot-email" class="form-control" placeholder="Enter Email Address"
                            required autocomplete="email">
                    </div>
                    <button type="button" id="btn-send-reset-otp" class="login-submit-btn w-100 m-0">Send OTP</button>
                    <div class="mt-3">
                        <a href="#" id="btn-back-from-forgot"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
                <!-- Step 4: Forgot Password – Enter OTP -->
                <div id="step-forgot-otp" class="form-step" style="display:none;">
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#555;margin-bottom:12px;">
                        OTP sent to <strong id="display-forgot-email"></strong>
                    </p>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="forgot-otp">Enter OTP</label>
                        <input type="text" id="forgot-otp" class="form-control" placeholder="Enter OTP" required
                            maxlength="6" inputmode="numeric">
                    </div>
                    <button type="button" id="btn-verify-reset-otp" class="login-submit-btn w-100 m-0">Verify OTP</button>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" id="btn-back-from-forgot-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="#" id="btn-resend-reset-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#1F5552;text-decoration:underline;">
                            Resend OTP
                        </a>
                    </div>
                </div>
                <!-- Step 5: Set New Password -->
                <div id="step-new-password" class="form-step" style="display:none;">
                    <h5 style="font-family:'Cinzel',serif;color:#1F5552;margin-bottom:14px;font-size:15px;">Set New Password
                    </h5>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="new-password">New Password</label>
                        <input type="password" id="new-password" class="form-control" placeholder="New Password" required
                            autocomplete="new-password">
                    </div>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" class="form-control" placeholder="Confirm Password"
                            required autocomplete="new-password">
                    </div>
                    <button type="button" id="btn-reset-password" class="login-submit-btn w-100 m-0">Reset Password</button>
                </div>
                <!-- Step: Guest Login – Enter Mobile -->
                <div id="step-guest" class="form-step" style="display:none;">
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="guest-mobile">Mobile Number</label>
                        <input type="text" id="guest-mobile" class="form-control" placeholder="Enter Mobile Number" required
                            maxlength="10" inputmode="numeric">
                    </div>
                    <button type="button" id="btn-guest-send-otp" class="login-submit-btn w-100 m-0">Send OTP</button>
                    <div class="mt-3">
                        <a href="#" id="btn-back-from-guest"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </div>
                <!-- Step: Guest Login – Enter OTP -->
                <div id="step-guest-otp" class="form-step" style="display:none;">
                    <p style="font-family:'Outfit',sans-serif;font-size:13px;color:#555;margin-bottom:12px;">
                        OTP sent to <strong id="display-guest-mobile"></strong>
                    </p>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="guest-otp">Enter OTP</label>
                        <input type="text" id="guest-otp" class="form-control" placeholder="Enter OTP" required
                            maxlength="6" inputmode="numeric">
                    </div>
                    <button type="button" id="btn-verify-guest-otp" class="login-submit-btn w-100 m-0">Verify &amp;
                        Login</button>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" id="btn-back-from-guest-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="#" id="btn-resend-guest-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#1F5552;text-decoration:underline;">
                            Resend OTP
                        </a>
                    </div>
                </div>
            </form>
            <p class="login-register-link mt-2">
                Don't have an account? <a href="{{ route('user.register') }}">Register</a>
            </p>
        </div>
    </section>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let currentEmail = '';

        function showStep(id) {
            document.querySelectorAll('.form-step').forEach(el => el.style.display = 'none');
            document.getElementById(id).style.display = 'block';
        }

        function isMobile(value) {
            return /^[6-9]\d{9}$/.test(value.trim());
        }

        function isEmail(value) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
        }

        // ── Safe wrapper: never let tracking break the login/redirect flow ──
        function safeFireTrackingEvents(events) {
            if (!events || (Array.isArray(events) && events.length === 0)) return;
            try {
                fireTrackingEvents(events);
            } catch (err) {
                console.error('Tracking events failed (ignored):', err);
            }
        }

        // ── Continue (detect mobile vs email) ──────────────────────────────
        document.getElementById('btn-continue').addEventListener('click', () => {
            const val = document.getElementById('login-input').value.trim();

            if (!val) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter your mobile number or email.', confirmButtonColor: '#1F5552' });
                return;
            }

            if (isMobile(val)) {
                fetch("{{ url('/user/send-login-otp') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({ mobile: val })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status) {
                            document.getElementById('display-mobile').textContent = val;
                            Swal.fire({ icon: 'success', title: 'OTP Sent', text: data.message, timer: 1500, showConfirmButton: false });
                            showStep('step-otp');
                        } else {
                            Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                        }
                    })
                    .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));

            } else if (isEmail(val)) {
                currentEmail = val;
                document.getElementById('display-email').textContent = val;
                showStep('step-password');
            } else {
                Swal.fire({ icon: 'warning', title: 'Invalid Input', text: 'Please enter a valid 10-digit mobile number or email address.', confirmButtonColor: '#1F5552' });
            }
        });

        document.getElementById('login-input').addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('btn-continue').click();
        });

        // ── OTP: Verify ────────────────────────────────────────────────────
        document.getElementById('btn-verify-otp').addEventListener('click', () => {
            fetch("{{ url('/user/verify-login-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ otp: document.getElementById('otp').value })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status && data.not_registered) {
                        Swal.fire({
                            icon: 'success',
                            title: 'OTP Verified',
                            text: 'Taking you to complete registration...',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => window.location.href = data.redirect);
                    } else if (data.status) {
                        safeFireTrackingEvents(data.tracking_events); // 👈 fixed
                        Swal.fire({ icon: 'success', title: 'Login Successful', text: 'Redirecting...', timer: 1500, showConfirmButton: false })
                            .then(() => window.location.href = data.redirect);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Invalid OTP', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── OTP: Resend ────────────────────────────────────────────────────
        document.getElementById('btn-resend-otp').addEventListener('click', e => {
            e.preventDefault();
            const mobile = document.getElementById('login-input').value.trim();
            fetch("{{ url('/user/send-login-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ mobile })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({ icon: 'success', title: 'OTP Resent', text: data.message, timer: 1500, showConfirmButton: false });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                });
        });

        // ── OTP: Back ──────────────────────────────────────────────────────
        document.getElementById('btn-back-from-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('otp').value = '';
            showStep('step-input');
        });

        // ── Password: Login ────────────────────────────────────────────────
        document.getElementById('btn-login-password').addEventListener('click', () => {
            fetch("{{ url('/user/login-email') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    email: currentEmail,
                    password: document.getElementById('password').value
                })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        safeFireTrackingEvents(data.tracking_events); // 👈 fixed
                        Swal.fire({ icon: 'success', title: 'Login Successful', text: 'Redirecting...', timer: 1500, showConfirmButton: false })
                            .then(() => window.location.href = data.redirect);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Login Failed', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        document.getElementById('password').addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('btn-login-password').click();
        });

        // ── Password: Back ─────────────────────────────────────────────────
        document.getElementById('btn-back-from-password').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('password').value = '';
            showStep('step-input');
        });

        // ── Forgot Password: open ──────────────────────────────────────────
        document.getElementById('btn-forgot-password').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('forgot-email').value = currentEmail;
            showStep('step-forgot-email');
        });

        // ── Forgot Password: Send OTP ──────────────────────────────────────
        document.getElementById('btn-send-reset-otp').addEventListener('click', () => {
            const email = document.getElementById('forgot-email').value.trim();
            if (!isEmail(email)) {
                Swal.fire({ icon: 'warning', title: 'Invalid Email', text: 'Please enter a valid email address.', confirmButtonColor: '#1F5552' });
                return;
            }
            fetch("{{ url('/user/send-password-reset-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ email })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('display-forgot-email').textContent = email;
                        Swal.fire({ icon: 'success', title: 'OTP Sent', text: data.message, timer: 1500, showConfirmButton: false });
                        showStep('step-forgot-otp');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── Forgot Password: Resend OTP ────────────────────────────────────
        document.getElementById('btn-resend-reset-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('btn-send-reset-otp').click();
        });

        // ── Forgot Password: Verify OTP ────────────────────────────────────
        document.getElementById('btn-verify-reset-otp').addEventListener('click', () => {
            fetch("{{ url('/user/verify-password-reset-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ otp: document.getElementById('forgot-otp').value })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({ icon: 'success', title: 'OTP Verified', text: data.message, timer: 1200, showConfirmButton: false });
                        showStep('step-new-password');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Invalid OTP', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── Forgot Password: Back buttons ──────────────────────────────────
        document.getElementById('btn-back-from-forgot').addEventListener('click', e => {
            e.preventDefault();
            showStep('step-password');
        });

        document.getElementById('btn-back-from-forgot-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('forgot-otp').value = '';
            showStep('step-forgot-email');
        });

        // ── Reset Password: Submit ─────────────────────────────────────────
        document.getElementById('btn-reset-password').addEventListener('click', () => {
            const newPwd = document.getElementById('new-password').value;
            const confirmPwd = document.getElementById('confirm-password').value;

            if (newPwd.length < 8) {
                Swal.fire({ icon: 'warning', title: 'Too Short', text: 'Password must be at least 8 characters.', confirmButtonColor: '#1F5552' });
                return;
            }
            if (newPwd !== confirmPwd) {
                Swal.fire({ icon: 'warning', title: 'Mismatch', text: 'Passwords do not match.', confirmButtonColor: '#1F5552' });
                return;
            }

            fetch("{{ url('/user/reset-password') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ password: newPwd, password_confirmation: confirmPwd })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Password Reset Successful',
                            html: 'Your password has been reset successfully.<br><br><a href="{{ route('user.login') }}" style="color:#1F5552;font-weight:600;text-decoration:underline;">Click here to Login</a>',
                            confirmButtonText: 'Go to Login',
                            confirmButtonColor: '#1F5552'
                        }).then(() => window.location.href = "{{ route('user.login') }}");
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── Guest Login: Toggle ────────────────────────────────────────────
        const guestLoginContainer = document.getElementById('guest-login-container');
        const guestOrSeparator = document.getElementById('guest-or-separator');

        document.getElementById('btn-choice-guest').addEventListener('click', () => {
            if (guestLoginContainer) guestLoginContainer.style.display = 'none';
            if (guestOrSeparator) guestOrSeparator.style.display = 'none';
            showStep('step-guest');
        });

        document.getElementById('btn-back-from-guest').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('guest-mobile').value = '';
            if (guestLoginContainer) guestLoginContainer.style.display = 'block';
            if (guestOrSeparator) guestOrSeparator.style.display = 'flex';
            showStep('step-input');
        });

        // ── Guest Login: Send OTP ──
        document.getElementById('btn-guest-send-otp').addEventListener('click', () => {
            const val = document.getElementById('guest-mobile').value.trim();

            if (!val || !isMobile(val)) {
                Swal.fire({ icon: 'warning', title: 'Invalid Input', text: 'Please enter a valid 10-digit mobile number.', confirmButtonColor: '#1F5552' });
                return;
            }

            fetch("{{ url('/user/send-login-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ mobile: val })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('display-guest-mobile').textContent = val;
                        Swal.fire({ icon: 'success', title: 'OTP Sent', text: data.message, timer: 1500, showConfirmButton: false });
                        showStep('step-guest-otp');
                    } else {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.', confirmButtonColor: '#1F5552' }));
        });

        // ── Guest Login: Verify OTP ──
        document.getElementById('btn-verify-guest-otp').addEventListener('click', () => {
            const otpVal = document.getElementById('guest-otp').value.trim();

            if (!otpVal) {
                Swal.fire({ icon: 'warning', title: 'Required', text: 'Please enter the OTP.', confirmButtonColor: '#1F5552' });
                return;
            }

            fetch("{{ url('/user/verify-login-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ otp: otpVal })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status && data.not_registered) {
                        fetch("{{ url('/user/guest-login') }}", {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf }
                        })
                            .then(r => r.json())
                            .then(guestData => {
                                if (guestData.status) {
                                    safeFireTrackingEvents(guestData.tracking_events); // 👈 fixed
                                    Swal.fire({ icon: 'success', title: 'Welcome!', text: 'Logging you in...', timer: 1500, showConfirmButton: false })
                                        .then(() => window.location.href = guestData.redirect);
                                }
                            });
                    } else if (data.status) {
                        safeFireTrackingEvents(data.tracking_events); // 👈 fixed
                        Swal.fire({ icon: 'success', title: 'Login Successful', text: 'Redirecting...', timer: 1500, showConfirmButton: false })
                            .then(() => window.location.href = data.redirect);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Invalid OTP', text: data.message, confirmButtonColor: '#1F5552' });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.', confirmButtonColor: '#1F5552' }));
        });

        // ── Guest Login: Resend OTP ──
        document.getElementById('btn-resend-guest-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('btn-guest-send-otp').click();
        });

        document.getElementById('guest-mobile').addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('btn-guest-send-otp').click();
        });

        document.getElementById('guest-otp').addEventListener('keydown', e => {
            if (e.key === 'Enter') document.getElementById('btn-verify-guest-otp').click();
        });

        // ── Guest Login: Back from OTP ─────────────────────────────────────
        document.getElementById('btn-back-from-guest-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('guest-otp').value = '';
            showStep('step-guest');
        });
    </script>
@endsection