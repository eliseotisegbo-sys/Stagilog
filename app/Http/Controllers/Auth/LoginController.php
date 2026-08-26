<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Afficher le formulaire de connexion école
     */
    public function showEcoleLoginForm()
    {
        return view('auth.login-ecole');
    }
    
    /**
     * Afficher le formulaire de connexion admin
     */
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }
    
    /**
     * Traiter la connexion école
     */
    public function loginEcole(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt(array_merge($credentials, ['role' => 'ecole']))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.ecole'));
        }
        
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }
    
    /**
     * Traiter la connexion admin
     */
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            
            // Vérifier si première connexion
            if (Auth::user()->first_login) {
                return redirect()->route('first-time-setup');
            }
            
            return redirect()->intended(route('dashboard.admin'));
        }
        
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }
    
    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
