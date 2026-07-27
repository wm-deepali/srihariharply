<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\Customer;
use App\Models\SmtpSetting;
use App\Services\Email\EmailDispatcher;
use App\Services\Sms\SmsDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;

class CustomerAuthController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────────
    // VIEWS
    // ──────────────────────────────────────────────────────────────────────────

    public function loginForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('user.profile');
        }
        return view('user.login');
    }

    public function registerForm()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('user.profile');
        }
        return view('user.register');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // REGISTER FLOW
    // ──────────────────────────────────────────────────────────────────────────

    public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|regex:/^[6-9]\d{9}$/'
        ]);

        $extras = $this->getOtpExtras();
        $maxRetries = (int) ($extras['max_retries'] ?? 3);
        $otpLength = (int) ($extras['otp_length'] ?? 6);

        // ── Max retry check ────────────────────────────────────────────────────
        $attempts = session('otp_attempts', 0);
        if ($attempts >= $maxRetries) {
            return response()->json([
                'status' => false,
                'message' => "Too many OTP requests. Please try again later.",
            ]);
        }

        $otp = $this->generateOtp($otpLength);

        session([
            'register_mobile' => $request->mobile,
            'register_otp' => $otp,
            'otp_attempts' => $attempts + 1,
        ]);

        $this->sendSms($request->mobile, $otp, $extras);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == session('register_otp')) {
            session(['otp_verified' => true]);
            session()->forget('otp_attempts');      // ← reset on success
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully.',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP.',
        ]);
    }

    /**
     * Called by register page on arrival via ?from=login.
     * verifyLoginOtp() already set otp_verified + register_mobile in the session,
     * so this just confirms those session keys are intact and returns the mobile.
     */
    public function trustLoginMobileCheck(Request $request)
    {
        if (session('otp_verified') && session('register_mobile')) {
            return response()->json([
                'status' => true,
                'mobile' => session('register_mobile'),
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Session expired. Please start again.',
        ]);
    }

    /** @deprecated Legacy endpoint kept for backwards compatibility. */
    public function trustLoginMobile(Request $request)
    {
        $request->validate([
            'mobile' => 'required|regex:/^[6-9]\d{9}$/'
        ]);

        if (session('otp_verified') && session('register_mobile') === $request->mobile) {
            return response()->json(['status' => true]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Session expired. Please start again.',
        ]);
    }

    public function register(Request $request)
    {
        if (!session('otp_verified')) {
            return response()->json([
                'status' => false,
                'message' => 'OTP verification required.',
            ]);
        }

        $validated = $request->validate(
            [
                'name' => ['required', 'max:100', 'regex:/^[A-Za-z\s]+$/'],
                'email' => ['required', 'email', 'unique:customers,email'],
                'password' => ['required', 'min:8', 'confirmed'],
            ],
            [
                'name.regex' => 'Name should contain only letters.',
                'password.confirmed' => 'Password and confirm password do not match.',
            ]
        );

        // ── Capture the guest session ID BEFORE Auth::login() regenerates it ──
        $guestSessionId = session()->getId();

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => session('register_mobile'),
            'alternate_mobile' => '9999999999',
            'password' => bcrypt($validated['password']),
        ]);

        Auth::guard('customer')->login($customer);
        $this->mergeGuestCart($customer, $guestSessionId);
        $this->mergeGuestWishlist($customer, $guestSessionId);

       session()->forget(['register_mobile', 'register_otp', 'otp_verified']);

    EmailDispatcher::send(
        'welcome',
        $customer->email,
        [
            '{customer_name}' => $customer->name,
        ]
    );

    $trackingEvents = \App\Services\Tracking\PixelTracker::signUpEvent('email_password'); // 👈 new

    return response()->json([
        'status' => true,
        'message' => 'Registration successful.',
        'redirect' => $this->intendedRedirect(),
        'tracking_events' => $trackingEvents, // 👈 new
    ]);
    }

  public function guestLogin(Request $request)
{
    if (!session('otp_verified')) {
        return response()->json([
            'status' => false,
            'message' => 'Please verify OTP first.',
        ]);
    }

    $mobile = session('register_mobile');

    $guestSessionId = session()->getId();

    $customer = Customer::firstOrCreate(
        ['mobile' => $mobile],
        [
            'name' => 'Guest User',
            'email' => null,
            'alternate_mobile' => '9999999999',
            'password' => bcrypt(str()->random(10)),
        ]
    );

    $isNewCustomer = $customer->wasRecentlyCreated; // 👈 new

    Auth::guard('customer')->login($customer);
    $this->mergeGuestCart($customer, $guestSessionId);
    $this->mergeGuestWishlist($customer, $guestSessionId);

    session()->forget(['register_mobile', 'register_otp', 'otp_verified']);

    $trackingEvents = $isNewCustomer
        ? \App\Services\Tracking\PixelTracker::signUpEvent('mobile_otp')
        : \App\Services\Tracking\PixelTracker::loginEvent(); // 👈 new

    return response()->json([
        'status' => true,
        'redirect' => $this->intendedRedirect(),
        'tracking_events' => $trackingEvents, // 👈 new
    ]);
}

    // ──────────────────────────────────────────────────────────────────────────
    // LOGIN FLOW – Mobile OTP
    // ──────────────────────────────────────────────────────────────────────────

    public function sendLoginOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|regex:/^[6-9]\d{9}$/'
        ]);

        $extras = $this->getOtpExtras();
        $maxRetries = (int) ($extras['max_retries'] ?? 3);
        $otpLength = (int) ($extras['otp_length'] ?? 6);

        // ── Max retry check ────────────────────────────────────────────────────
        $attempts = session('login_otp_attempts', 0);
        if ($attempts >= $maxRetries) {
            return response()->json([
                'status' => false,
                'message' => "Too many OTP requests. Please try again later.",
            ]);
        }

        $customer = Customer::where('mobile', $request->mobile)->first();
        $otp = $this->generateOtp($otpLength);

        session([
            'login_mobile' => $request->mobile,
            'login_otp' => $otp,
            'login_is_new' => !$customer,
            'login_otp_attempts' => $attempts + 1,
        ]);

        $this->sendSms($request->mobile, $otp, $extras);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
        ]);
    }

    public function verifyLoginOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp != session('login_otp')) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.',
            ]);
        }

        session()->forget('login_otp_attempts');

        // ── New user: transfer session to register flow and send them to register page ──
        if (session('login_is_new')) {
            session([
                'register_mobile' => session('login_mobile'),
                'otp_verified' => true,
            ]);
            session()->forget(['login_mobile', 'login_otp', 'login_is_new']);

            return response()->json([
                'status' => true,
                'not_registered' => true,
                'redirect' => route('user.register') . '?from=login',
            ]);
        }

       // ── Existing user: log them in ──────────────────────────────────────
$customer = Customer::where('mobile', session('login_mobile'))->first();

if (!$customer) {
    return response()->json([
        'status' => false,
        'message' => 'Customer not found.',
    ]);
}

$guestSessionId = session()->getId();

Auth::guard('customer')->login($customer);
$this->mergeGuestCart($customer, $guestSessionId);
$this->mergeGuestWishlist($customer, $guestSessionId);

session()->forget(['login_mobile', 'login_otp', 'login_is_new']);

$trackingEvents = \App\Services\Tracking\PixelTracker::loginEvent(); // 👈 new

return response()->json([
    'status' => true,
    'redirect' => $this->intendedRedirect(),
    'tracking_events' => $trackingEvents, // 👈 new
]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // LOGIN FLOW – Email + Password
    // ──────────────────────────────────────────────────────────────────────────

   public function loginEmail(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $customer = Customer::where('email', $request->email)->first();

    if (!$customer || !Hash::check($request->password, $customer->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password.',
        ]);
    }

    $guestSessionId = session()->getId();

    Auth::guard('customer')->login($customer);
    $this->mergeGuestCart($customer, $guestSessionId);
    $this->mergeGuestWishlist($customer, $guestSessionId);

    $request->session()->regenerate();

    $trackingEvents = \App\Services\Tracking\PixelTracker::loginEvent(); // 👈 new

    return response()->json([
        'status' => true,
        'redirect' => $this->intendedRedirect(),
        'tracking_events' => $trackingEvents, // 👈 new
    ]);
}

    // ──────────────────────────────────────────────────────────────────────────
    // FORGOT PASSWORD FLOW
    // ──────────────────────────────────────────────────────────────────────────

    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'No account found with this email address.',
            ]);
        }

        $otp = rand(100000, 999999);

        session([
            'reset_email' => $request->email,
            'reset_otp' => $otp,
        ]);

        // Send OTP via email
        $this->sendEmailOtp($request->email, $otp);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent to your email address.',
        ]);
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp != session('reset_otp')) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.',
            ]);
        }

        session(['reset_otp_verified' => true]);

        return response()->json([
            'status' => true,
            'message' => 'OTP verified.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        if (!session('reset_otp_verified')) {
            return response()->json([
                'status' => false,
                'message' => 'OTP verification required.',
            ]);
        }

        $request->validate(
            [
                'password' => ['required', 'min:8', 'confirmed'],
            ],
            [
                'password.confirmed' => 'Passwords do not match.',
            ]
        );

        $customer = Customer::where('email', session('reset_email'))->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found.',
            ]);
        }

        $customer->update(['password' => bcrypt($request->password)]);

        session()->forget(['reset_email', 'reset_otp', 'reset_otp_verified']);

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully.',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // GOOGLE OAUTH
    // ──────────────────────────────────────────────────────────────────────────

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

   public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();

    $customer = Customer::firstOrCreate(
        ['email' => $googleUser->email],
        [
            'name' => $googleUser->name,
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
            'password' => bcrypt(str()->random(20)),
            'mobile' => time(),
            'alternate_mobile' => time() + 1,
        ]
    );

    $isNewCustomer = $customer->wasRecentlyCreated; // 👈 new

    $guestSessionId = session()->getId();

    Auth::guard('customer')->login($customer);
    $this->mergeGuestCart($customer, $guestSessionId);
    $this->mergeGuestWishlist($customer, $guestSessionId);

    // 👇 new — flash for session-based tracking (like Lead event)
    if ($isNewCustomer) {
        session()->flash('fire_signup_event', 'google_oauth');
    } else {
        session()->flash('fire_login_event', true);
    }

    return redirect()->intended(route('home'));
}
    // ──────────────────────────────────────────────────────────────────────────
    // LOGOUT
    // ──────────────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────────────────────────────────────

    private function sendSms(string $mobile, int $otp, array $extras = []): void
    {
        // Load extras if not passed (fallback for any direct calls)
        if (empty($extras)) {
            $extras = $this->getOtpExtras();
        }

        $otpExpiry = (int) ($extras['otp_expiry'] ?? 10);

        SmsDispatcher::send('otp', $mobile, [
            '{otp}' => (string) $otp,
            '{otp_expiry}' => (string) $otpExpiry,
            '{brand_name}' => config('app.name'),
            '{store_name}' => config('app.name'),
            '{website_url}' => url('/'),
        ]);
    }

    private function getOtpExtras(): array
    {
        $template = \App\Models\SmsTemplate::where('event_key', 'otp')->first();
        return $template?->extra_settings ?? [];
    }

    private function generateOtp(int $length = 6): int
    {
        $min = (int) str_pad('1', $length, '0');
        $max = (int) str_pad('', $length, '9');
        return rand($min, $max);
    }


    private function sendEmailOtp(string $email, int $otp): void
    {
        $customer = Customer::where('email', $email)->first();
        $customerName = $customer?->name ?? 'Valued Customer';

        $extras = \App\Models\EmailTemplate::where('event_key', 'password-reset')->first()?->extra_settings ?? [];
        $otpExpiry = (int) ($extras['otp_expiry_minutes'] ?? 10);

        EmailDispatcher::send('password-reset', $email, [
            '{customer_name}' => $customerName,
            '{email}' => $email,
            '{otp}' => (string) $otp,
            '{otp_expiry}' => (string) $otpExpiry,
        ]);
    }

    /**
     * Merge a guest's cart (keyed by their pre-login session ID) into the
     * customer's cart. The caller MUST capture $guestSessionId via
     * session()->getId() BEFORE calling Auth::guard('customer')->login(),
     * since login() regenerates the session ID internally
     * (SessionGuard::updateSession() -> $session->migrate(true)).
     * Reading session()->getId() after login() here would return the new
     * post-login ID and silently fail to find the guest's cart.
     */
    private function mergeGuestCart(Customer $customer, string $guestSessionId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)->first();

        if (!$guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => $customer->id],
            ['session_id' => $guestSessionId, 'total_amount' => 0]
        );

        if ($guestCart->id == $userCart->id) {
            $userCart->update(['user_id' => $customer->id]);
            return;
        }

        foreach ($guestCart->items as $item) {
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $item->product_id)
                ->first();

            if ($existingItem) {
                $existingItem->quantity += $item->quantity;
                $existingItem->total = $existingItem->quantity * $existingItem->price;
                $existingItem->save();
            } else {
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }
        }

        $userCart->update(['total_amount' => $userCart->items()->sum('total')]);
        $guestCart->items()->delete();
        $guestCart->delete();
    }

    /**
     * Merge a guest's wishlist (keyed by their pre-login session ID) into the
     * customer's wishlist. Same session-ID-capture rule as mergeGuestCart().
     */
    private function mergeGuestWishlist(Customer $customer, string $guestSessionId): void
    {
        $guestItems = Wishlist::where('session_id', $guestSessionId)->get();

        if ($guestItems->isEmpty()) {
            return;
        }

        foreach ($guestItems as $item) {
            $exists = Wishlist::where('customer_id', $customer->id)
                ->where('product_id', $item->product_id)
                ->exists();

            if (!$exists) {
                Wishlist::create([
                    'customer_id' => $customer->id,
                    'session_id' => null,
                    'product_id' => $item->product_id,
                    'expires_at' => $item->expires_at,
                ]);
            }
        }

        Wishlist::where('session_id', $guestSessionId)->delete();
    }

    /**
     * Checkout se aaya → checkout pe wapas
     * Direct login → profile pe
     */
    private function intendedRedirect(): string
    {
        $intended = session()->pull('url.intended');

        if ($intended && str_contains($intended, '/checkout')) {
            return $intended;
        }

        return route('user.profile');
    }
}