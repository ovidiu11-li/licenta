<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            $user = $request->user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard?verified=1');
                case 'teacher':
                    return redirect()->intended('/teacher/dashboard?verified=1');
                case 'student':
                default:
                    return redirect()->intended('/student/dashboard?verified=1');
            }
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $user = $request->user();
        switch ($user->role) {
            case 'admin':
                return redirect()->intended('/admin/dashboard?verified=1');
            case 'teacher':
                return redirect()->intended('/teacher/dashboard?verified=1');
            case 'student':
            default:
                return redirect()->intended('/student/dashboard?verified=1');
        }
    }
}
