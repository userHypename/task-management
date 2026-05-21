<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the current user
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount()
    {
        $user = Auth::user();
        $count = $user->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent unread notifications (for dropdown)
     */
    public function unread()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Authorize: user can only mark their own notifications
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        // Authorize: user can only delete their own notifications
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    /**
     * Get notification dropdown HTML (for AJAX)
     */
    public function dropdown()
    {
        $user = Auth::user();
        $unreadNotifications = $user->notifications()
            ->unread()
            ->latest()
            ->limit(10)
            ->get();

        return view('components.notification-dropdown', compact('unreadNotifications'));
    }
}
