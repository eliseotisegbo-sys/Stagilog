<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnexionHistorique extends Model
{
    protected $table = 'connexions_historique';
    protected $primaryKey = 'id_connexion';

    protected $fillable = [
        'id_user',
        'email',
        'role',
        'ip_address',
        'user_agent',
        'navigateur',
        'appareil',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Enregistrer une tentative / connexion
     */
    public static function logConnexion($user, $request, $statut = 'succes')
    {
        $userAgent = $request->header('User-Agent') ?? '';
        
        // Détection simple du navigateur
        $browser = 'Navigateur standard';
        if (str_contains($userAgent, 'Edg')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($userAgent, 'Chrome')) {
            $browser = 'Google Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Mozilla Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            $browser = 'Apple Safari';
        }

        // Détection simple de l'appareil
        $device = 'Ordinateur';
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone')) {
            $device = 'Mobile / Smartphone';
        } elseif (str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet')) {
            $device = 'Tablette';
        }

        return self::create([
            'id_user' => $user ? $user->id : null,
            'email' => $user ? $user->email : $request->input('email', 'inconnu'),
            'role' => $user ? $user->role : null,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'navigateur' => $browser,
            'appareil' => $device,
            'statut' => $statut,
        ]);
    }
}
