<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminGuard
{
    private const SESSION_LAST_ACTIVITY = 'admin_last_activity';
    private const SESSION_FAILED_ATTEMPTS = 'admin_failed_attempts';
    private const SESSION_LOCKED_UNTIL = 'admin_locked_until';

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $guardName = $guard ?: config('auth.defaults.guard');
        $auth = Auth::guard($guardName);
        $session = $request->session();

        if ($this->isLocked($session)) {
            return $this->reject($request);
        }

        if (! $auth->check()) {
            $this->incrementFailedAttempts($session);

            return $this->reject($request);
        }

        $this->assertRecentActivity($session, $auth);

        $response = $next($request);

        if ($session->isStarted()) {
            $session->migrate(true);
        }

        return $response;
    }

    private function isLocked($session): bool
    {
        $lockedUntil = $session->get(self::SESSION_LOCKED_UNTIL);

        if (! $lockedUntil) {
            return false;
        }

        $expiresAt = Carbon::parse($lockedUntil);

        if ($expiresAt->isFuture()) {
            return true;
        }

        $session->forget([self::SESSION_LOCKED_UNTIL, self::SESSION_FAILED_ATTEMPTS]);

        return false;
    }

    private function incrementFailedAttempts($session): void
    {
        $attempts = (int) $session->get(self::SESSION_FAILED_ATTEMPTS, 0) + 1;
        $session->put(self::SESSION_FAILED_ATTEMPTS, $attempts);

        $maxAttempts = (int) config('hisar.security.admin_max_attempts', 5);
        if ($attempts < $maxAttempts) {
            return;
        }

        $lockSeconds = (int) config('hisar.security.admin_lockout_seconds', 120);
        $session->put(
            self::SESSION_LOCKED_UNTIL,
            now()->addSeconds($lockSeconds)->toIso8601String()
        );
    }

    private function assertRecentActivity($session, $auth): void
    {
        $timeoutMinutes = (int) config('hisar.security.admin_timeout_minutes', 30);
        $lastActivity = $session->get(self::SESSION_LAST_ACTIVITY);

        if ($lastActivity) {
            $lastSeen = Carbon::parse($lastActivity);
            if ($lastSeen->diffInMinutes(now()) >= $timeoutMinutes) {
                $auth->logout();
                $session->invalidate();
                $session->regenerateToken();

                abort(440, 'Oturum zaman aşımına uğradı.');
            }
        }

        $session->put(self::SESSION_LAST_ACTIVITY, now()->toIso8601String());
    }

    private function reject(Request $request): Response
    {
        if (Route::has('admin.login')) {
            return redirect()->route('admin.login');
        }

        abort(403, 'Yetkisiz erişim.');
    }
}
