<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');

        // Try the normal Laravel attempt first (will handle standard hashed passwords)
        try {
            if (Auth::attempt($credentials, $this->boolean('remember'))) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } catch (\RuntimeException $e) {
            // Some hashing drivers (or verify settings) may throw on algorithm mismatch.
            // We'll fall through to try legacy verification below.
        }

        // Fallback: try to authenticate legacy/other-hash formats and migrate them to bcrypt.
        $user = User::where('email', (string) $this->string('email'))->first();

        if ($user) {
            // Always work with a plain string here, not a Stringable instance
            $plain = (string) $this->string('password');

            // Plaintext stored password (dev/test databases sometimes use plaintext) — rehash and login.
            if ($user->password === $plain) {
                $user->password = Hash::make($plain);
                $user->save();
                Auth::login($user, $this->boolean('remember'));
                RateLimiter::clear($this->throttleKey());
                return;
            }

            // MD5 legacy hashes
            if (strlen($user->password) === 32 && md5($plain) === $user->password) {
                $user->password = Hash::make($plain);
                $user->save();
                Auth::login($user, $this->boolean('remember'));
                RateLimiter::clear($this->throttleKey());
                return;
            }

            // SHA1 legacy hashes
            if (strlen($user->password) === 40 && sha1($plain) === $user->password) {
                $user->password = Hash::make($plain);
                $user->save();
                Auth::login($user, $this->boolean('remember'));
                RateLimiter::clear($this->throttleKey());
                return;
            }

            // As last attempt, try password_verify (handles non-bcrypt formatted hashes like Argon, etc.)
            try {
                if (password_verify($plain, $user->password)) {
                    // If password_verify succeeds but Laravel threw earlier due to algorithm verify,
                    // rehash into Laravel's default hasher so subsequent logins use Laravel Hash::check.
                    $user->password = Hash::make($plain);
                    $user->save();
                    Auth::login($user, $this->boolean('remember'));
                    RateLimiter::clear($this->throttleKey());
                    return;
                }
            } catch (\Throwable $e) {
                // ignore and continue to fail below
            }
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
