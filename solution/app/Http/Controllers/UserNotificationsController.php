<?php

namespace App\Http\Controllers;

class UserNotificationsController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user's notifications.
     *
     */
    public function index()
    {
        // The following query causes an N+1 query, performance wise it's not good.
        // current_user()->unreadNotifications->markAsRead();
        // Mark all unread notifications as
        $notifications = current_user()->notifications;
        current_user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return view('notifications', ['notifications' => $notifications]);
    }
}
