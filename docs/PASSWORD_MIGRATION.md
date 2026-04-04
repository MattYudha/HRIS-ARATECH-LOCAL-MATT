## Password migration & compatibility notes

This project uses Laravel's default hashing (bcrypt via Hash::make / Hash::check). During development I found operations that could throw when an existing user password in the database is not a bcrypt hash (for example legacy MD5 or plaintext data). To make login more resilient and to migrate legacy passwords safely, the login flow now includes a fallback re-hash step.

What I implemented
- Updated `app/Http/Requests/Auth/LoginRequest.php` so login attempts:
  1. Try the normal Laravel `Auth::attempt` (the secure, default path)
  2. If that fails or a hashing runtime exception occurs, the code checks the stored value and attempts a safe legacy verification (checks for plaintext, MD5 or SHA1). On success the password is re-hashed using Laravel `Hash::make` and the user is logged in.

Why this is safe
- Legacy checks are only performed after the normal attempt fails and only for that single login attempt.
- On successful legacy verification, the password is immediately converted to a secure bcrypt hash.

If you'd prefer not to enable fallbacks in code, you can force a user password reset / let users use the password reset flow to set a new bcrypt password.

-- End of file
