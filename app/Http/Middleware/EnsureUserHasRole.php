<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * التحقق من امتلاك المستخدم أحد الأدوار المحددة
     *
     * الاستخدام في Routes:
     *   ->middleware('role:مدير النظام')
     *   ->middleware('role:مدير النظام,محامي')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. التأكد من أن المستخدم مسجَّل دخول
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // 2. التأكد من أن الحساب مفعّل
        if (!$request->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'حسابك موقوف. تواصل مع مدير النظام.');
        }

        // 3. التحقق من الدور
        if (!$request->user()->hasAnyRole($roles)) {
            abort(403, 'غير مصرّح لك بالوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}