@extends('layouts.user-auth-app')
@section('content')

    <section class="login-fullscreen-section">
        <div class="login-card">
            <h1 class="login-title">REGISTER</h1>

            <div class="login-decorative-divider">
                <img src="{{ asset('assets/images/login_card_innericon.png')}}" alt="La Pavone Emblem"
                    class="login-decorative-icon" onerror="this.style.display='none'">
            </div>

            <form class="login-form" id="register-form">

                <!-- Step 1: Enter Mobile Number -->
                <div id="step-mobile" class="form-step">
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="mobile">Mobile Number</label>
                        <input type="tel" id="mobile" class="form-control"
                            placeholder="Enter Mobile Number"  pattern="[0-9]{10}" inputmode="numeric">
                    </div>
                    <button type="button" id="btn-send-otp" class="login-submit-btn w-100 m-0">Send OTP</button>
                </div>

                <!-- Step 2: Enter OTP -->
                <div id="step-otp" class="form-step" style="display:none;">
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="otp">Enter OTP</label>
                        <input type="text" id="otp" class="form-control"
                            placeholder="Enter OTP"  maxlength="6" inputmode="numeric">
                    </div>
                    <button type="button" id="btn-verify-otp" class="login-submit-btn w-100 m-0">Verify OTP</button>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" id="btn-back-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#666;text-decoration:underline;">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="#" id="btn-resend-otp"
                            style="font-family:'Outfit',sans-serif;font-size:12px;color:#1F5552;text-decoration:underline;">
                            Resend OTP
                        </a>
                    </div>
                </div>

                <!-- Step 2.5: Choose Guest or Register -->
                <div id="step-choice" class="form-step" style="display:none;text-align:center;">
                    <h4 style="font-family:'Cinzel',serif;color:#1F5552;margin-bottom:20px;">Verification Successful</h4>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#444;margin-bottom:30px;">
                        How would you like to proceed?
                    </p>
                    <button type="button" id="btn-choice-guest"
                        class="login-submit-btn w-100 mb-3" style="margin:0;">
                        Guest Login
                    </button>
                    <button type="button" id="btn-choice-register"
                        class="login-submit-btn w-100"
                        style="margin:0;background-color:transparent;color:#1F5552;border:1px solid #1F5552;">
                        Register
                    </button>
                </div>

                <!-- Step 3: Registration Profile Details -->
                <div id="step-details" class="form-step" style="display:none;">
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="fullname">Full Name</label>
                        <input type="text" id="fullname" class="form-control" placeholder="Full Name" >
                    </div>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="email">Email Id</label>
                        <input type="email" id="email" class="form-control" placeholder="Email Id" >
                    </div>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="password">Enter Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter Password"  autocomplete="new-password">
                    </div>
                    <div class="form-group mb-3">
                        <label class="visually-hidden" for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password" autocomplete="new-password">
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-3 login-options">
                        <div class="form-check m-0 p-0">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="login-submit-btn m-0" style="width:70%;">Register</button>
                    </div>
                </div>

            </form>

            <p class="login-register-link mt-2">
                Already have an account? <a href="{{ route('user.login') }}">Login</a>
            </p>
        </div>
    </section>

    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;

        function showStep(id) {
            document.querySelectorAll('.form-step').forEach(el => el.style.display = 'none');
            document.getElementById(id).style.display = 'block';
        }

        // ── Auto-land on step-choice if coming from login with unregistered mobile ──
        (function () {
            const params = new URLSearchParams(window.location.search);

            if (params.get('from') === 'login') {
                // Server session already has otp_verified=true and register_mobile set
                // by verifyLoginOtp(). Call trustLoginMobile to confirm and get the mobile.
                fetch("{{ url('/user/trust-login-mobile-check') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify({})
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        if (data.mobile) {
                            document.getElementById('mobile').value = data.mobile;
                        }
                        showStep('step-choice');
                    } else {
                        showStep('step-mobile');
                    }
                })
                .catch(() => showStep('step-mobile'));
            }
        })();

        // ── Send OTP ────────────────────────────────────────────────────────
        document.getElementById('btn-send-otp').addEventListener('click', () => {
            const mobile = document.getElementById('mobile').value.trim();
            if (!/^[6-9]\d{9}$/.test(mobile)) {
                Swal.fire({ icon: 'warning', title: 'Invalid Number', text: 'Please enter a valid 10-digit mobile number.', confirmButtonColor: '#1F5552' });
                return;
            }
            fetch("{{ url('/user/send-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ mobile })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    Swal.fire({ icon: 'success', title: 'OTP Sent', text: data.message, timer: 1500, showConfirmButton: false });
                    showStep('step-otp');
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── Verify OTP ──────────────────────────────────────────────────────
        document.getElementById('btn-verify-otp').addEventListener('click', () => {
            fetch("{{ url('/user/verify-otp') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ otp: document.getElementById('otp').value })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    Swal.fire({ icon: 'success', title: 'OTP Verified', text: data.message, timer: 1500, showConfirmButton: false });
                    showStep('step-choice');
                } else {
                    Swal.fire({ icon: 'error', title: 'Invalid OTP', text: data.message, confirmButtonColor: '#1F5552' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });

        // ── Back from OTP ───────────────────────────────────────────────────
        document.getElementById('btn-back-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('otp').value = '';
            showStep('step-mobile');
        });

        // ── Resend OTP ──────────────────────────────────────────────────────
        document.getElementById('btn-resend-otp').addEventListener('click', e => {
            e.preventDefault();
            document.getElementById('btn-send-otp').click();
        });

        // ── Guest Login ─────────────────────────────────────────────────────
        document.getElementById('btn-choice-guest').addEventListener('click', () => {
            fetch("{{ url('/user/guest-login') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                                fireTrackingEvents(data.tracking_events); // 👈 new

                    Swal.fire({ icon: 'success', title: 'Welcome', text: 'Logging you in...', timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = data.redirect);
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                }
            });
        });

        // ── Register Choice ─────────────────────────────────────────────────
        document.getElementById('btn-choice-register').addEventListener('click', () => {
            showStep('step-details');
        });

        // ── Register Submit ─────────────────────────────────────────────────
        document.getElementById('register-form').addEventListener('submit', function (e) {
            e.preventDefault();
            fetch("{{ url('/user/register') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({
                    name: document.getElementById('fullname').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                               fireTrackingEvents(data.tracking_events); // 👈 fixed: was "res", now "data"

                    Swal.fire({ icon: 'success', title: 'Registration Successful', text: data.message, timer: 1500, showConfirmButton: false })
                        .then(() => window.location.href = data.redirect);
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message, confirmButtonColor: '#1F5552' });
                }
            })
            .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong. Please try again.', confirmButtonColor: '#1F5552' }));
        });
    </script>

@endsection