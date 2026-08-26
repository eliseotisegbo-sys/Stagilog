<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstTimeSetupController extends Controller
{
    /**
     * Afficher le formulaire de premier paramétrage
     */
    public function show()
    {
        if (!Auth::check() || !Auth::user()->first_login) {
            return redirect()->route('dashboard.admin');
        }
        
        return view('auth.first-time-setup');
    }
    
    /**
     * Traiter le changement de mot de passe
     */
    public function update(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], [
            'new_password.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.',
        ]);
        
        $user = Auth::user();
        
        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mot de passe actuel incorrect.']);
        }
        
        // Mettre à jour
        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->first_login_at = now();
        $user->save();
        
        return redirect()->route('dashboard.admin')->with('success', 'Mot de passe défini avec succès !');
    }
}
