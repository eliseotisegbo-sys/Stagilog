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
        'nom',
        'role',
        'ip_address',
        'user_agent',
        'navigateur',
        'appareil',
        'session_id',
        'statut',
        'deconnecte_at',
    ];

    protected $casts = [
        'deconnecte_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Enregistrer une tentative / connexion
     */
    public static function logConnexion($user, $request, $statut = 'succes', $nom = null)
    {
        $userAgent = $request->header('User-Agent') ?? '';
        
        // Détection du navigateur
        $browser = 'Navigateur web';
        if (str_contains($userAgent, 'Edg')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($userAgent, 'Chrome')) {
            $browser = 'Google Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Mozilla Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            $browser = 'Apple Safari';
        }

        // Détection de l'appareil
        $device = 'Ordinateur';
        if (str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone')) {
            $device = 'Smartphone';
        } elseif (str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet')) {
            $device = 'Tablette';
        }

        $userName = $nom ?? ($user ? $user->name : $request->input('name', $request->input('email', 'Invité')));
        $sessionId = $request->session()->getId();

        return self::create([
            'id_user' => $user ? $user->id : null,
            'email' => $user ? $user->email : $request->input('email', 'inconnu'),
            'nom' => $userName,
            'role' => $user ? $user->role : null,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'navigateur' => $browser,
            'appareil' => $device,
            'session_id' => $sessionId,
            'statut' => $statut,
        ]);
    }

    /**
     * Enregistrer la déconnexion
     */
    public static function logDeconnexion($user, $request)
    {
        $sessionId = $request->session()->getId();
        
        // Mettre à jour la dernière connexion active de cette session ou de cet utilisateur
        $lastConnexion = self::where('id_user', $user->id)
            ->where('statut', 'succes')
            ->whereNull('deconnecte_at')
            ->latest()
            ->first();

        if ($lastConnexion) {
            $lastConnexion->update([
                'deconnecte_at' => now(),
            ]);
        }
    }
}
