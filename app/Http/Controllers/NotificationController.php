<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * عرض كل الإشعارات
     */
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(20);

        return view('cms.notifications.index', compact('notifications'));
    }

    /**
     * عرض الإشعارات غير المقروءة فقط
     */
    public function unread(): View
    {
        $notifications = auth()->user()
            ->unreadNotifications()
            ->paginate(20);

        return view('cms.notifications.index', compact('notifications'));
    }

    /**
     * تعليم إشعار واحد كمقروء
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        // إعادة التوجيه للرابط (إن وُجد)
        $url = $notification->data['url'] ?? route('notifications.index');

        return redirect($url);
    }

    /**
     * تعليم كل الإشعارات كمقروءة
     */
    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'تم تعليم جميع الإشعارات كمقروءة.');
    }

    /**
     * حذف إشعار
     */
    public function destroy(string $id): RedirectResponse
    {
        auth()->user()
            ->notifications()
            ->findOrFail($id)
            ->delete();

        return back()->with('success', 'تم حذف الإشعار.');
    }

    /**
     * حذف كل الإشعارات المقروءة
     */
    public function clearRead(): RedirectResponse
    {
        auth()->user()
            ->readNotifications()
            ->delete();

        return back()->with('success', 'تم حذف الإشعارات المقروءة.');
    }
}