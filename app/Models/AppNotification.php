<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'app_notifications';

    protected $fillable = [
        'id_user',
        'id_ecole',
        'target_role',
        'titre',
        'message',
        'type',
        'lien',
        'lu',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    /**
     * Scope pour les notifications de l'utilisateur connecté
     */
    public function scopeForCurrentUser($query)
    {
        $user = auth()->user();
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->isAdmin()) {
            return $query->where(function($q) use ($user) {
                $q->where('target_role', 'admin')
                  ->orWhere('target_role', 'all')
                  ->orWhere('id_user', $user->id);
            });
        }

        // Pour les écoles
        return $query->where(function($q) use ($user) {
            $q->where(function($sub) use ($user) {
                $sub->where('target_role', 'ecole')
                    ->where('id_ecole', $user->id_ecole);
            })
            ->orWhere('id_user', $user->id)
            ->orWhere('target_role', 'all');
        });
    }

    /**
     * Méthode statique pour créer rapidement une notification
     */
    public static function notifier($targetRole, $titre, $message, $lien = null, $type = 'info', $idEcole = null, $idUser = null)
    {
        return self::create([
            'target_role' => $targetRole,
            'id_ecole' => $idEcole,
            'id_user' => $idUser,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
            'lien' => $lien,
            'lu' => false,
        ]);
    }
}
