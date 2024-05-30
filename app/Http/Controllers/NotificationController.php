<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Notification::all());
    }
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read_at = now();
        $notification->save();

        return response()->json(['success' => true]);
    }
}
