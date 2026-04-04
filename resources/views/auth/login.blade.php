<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6 text-center" :status="session('status')" />

    <!-- Logo & Tagline -->
    <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:24px;">
        <img
            src="{{ asset('img/HRIS ARATECH logo tr.png') }}"
            alt="HRIS Aratech Logo"
            style="height:140px; width:auto; margin-bottom:4px;"
        >
        <p class="login-subtitle" style="font-size:14px; margin:0;">
            Human Resource Information System
        </p>
        <p class="login-company" style="font-size:14px; margin:4px 0 0 0;">
            PT Aratech Nusantara Indonesia
        </p>
    </div>

    <!-- Login Form -->
    <div style="width:100%; max-width:28rem; margin:0 auto;">
        <form method="POST" action="{{ route('login') }}" style="display:flex; flex-direction:column; gap:16px;">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="input-label" style="display:block; font-size:14px; font-weight:500; margin-bottom:4px;">
                    {{ __('Email Address') }}
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    style="display:block; width:100%; padding:8px 12px; border-radius:8px; border:1px solid; font-size:14px; outline:none; transition: all 0.2s ease; box-sizing:border-box;"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="input-label" style="display:block; font-size:14px; font-weight:500; margin-bottom:4px;">
                    {{ __('Password') }}
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    style="display:block; width:100%; padding:8px 12px; border-radius:8px; border:1px solid; font-size:14px; outline:none; transition: all 0.2s ease; box-sizing:border-box;"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember & Forgot -->
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <label for="remember_me" style="display:inline-flex; align-items:center; cursor:pointer;">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        style="border-radius:4px; margin-right:8px;"
                    >
                    <span class="remember-text" style="font-size:14px;">
                        {{ __('Remember me') }}
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="forgot-link"
                        style="font-size:14px; text-decoration:none;"
                    >
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div style="padding-top:8px;">
                <button
                    type="submit"
                    style="width:100%; padding:10px 16px; background-color:#4f46e5; color:white; font-weight:600; font-size:14px; text-transform:uppercase; letter-spacing:1px; border:none; border-radius:8px; cursor:pointer; transition:background-color 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#4338ca'"
                    onmouseout="this.style.backgroundColor='#4f46e5'"
                >
                    {{ __('Sign In') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>