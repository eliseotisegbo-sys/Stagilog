<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Obtenir les dernières notifications de l'utilisateur connecté
     */
    public function getNotifications()
    {
        $notifications = AppNotification::forCurrentUser()
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = AppNotification::forCurrentUser()
            ->where('lu', false)
            ->count();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        AppNotification::forCurrentUser()
            ->where('lu', false)
            ->update(['lu' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Marquer une notification spécifique comme lue et rediriger vers son lien
     */
    public function readAndRedirect($id)
    {
        $notification = AppNotification::forCurrentUser()->findOrFail($id);
        $notification->lu = true;
        $notification->save();

        if ($notification->lien) {
            return redirect($notification->lien);
        }

        return back();
    }
}
