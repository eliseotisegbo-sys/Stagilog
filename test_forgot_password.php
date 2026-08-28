<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;

echo "=== SIMULATION COMPLÈTE MOT DE PASSE OUBLIÉ ===\n\n";

$testEmail = 'stagilogtfg@gmail.com'; // Email de test

try {
    // 1. Chercher l'utilisateur
    echo "1. Recherche utilisateur avec email: {$testEmail}\n";
    $user = User::where('email', $testEmail)->first();
    
    if (!$user) {
        // Créer un utilisateur de test si nécessaire
        echo "   Aucun utilisateur trouvé, utilisation du premier admin...\n";
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::first();
        }
    }
    
    echo "   ✅ Utilisateur trouvé: {$user->name} ({$user->email})\n\n";
    
    // 2. Générer code
    echo "2. Génération code à 6 chiffres...\n";
    $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    echo "   Code généré: {$code}\n\n";
    
    // 3. Stocker en cache
    echo "3. Stockage en cache...\n";
    Cache::put("password_reset_{$testEmail}", [
        'code' => $code,
        'user_id' => $user->id,
        'email' => $testEmail,
    ], now()->addMinutes(15));
    echo "   ✅ Code stocké pour 15 minutes\n\n";
    
    // 4. Envoyer l'email
    echo "4. Envoi de l'email à: {$testEmail}\n";
    Mail::to($testEmail)->send(new PasswordResetCodeMail($code, $user));
    echo "   ✅ Email envoyé avec succès!\n\n";
    
    echo "=== TEST COMPLET RÉUSSI ===\n\n";
    echo "📧 Vérifiez l'email: {$testEmail}\n";
    echo "🔑 Code de test: {$code}\n";
    echo "⏰ Valable 15 minutes\n\n";
    
    echo "VÉRIFICATIONS À FAIRE:\n";
    echo "1. Boîte de réception: {$testEmail}\n";
    echo "2. Dossier SPAM/Courrier indésirable\n";
    echo "3. Onglet \"Promotions\" (si Gmail)\n\n";
    
    echo "Si l'email n'arrive toujours pas après 2-3 minutes:\n";
    echo "- Vérifiez les paramètres SMTP dans .env\n";
    echo "- Vérifiez que le mot de passe d'application Gmail est correct\n";
    echo "- Consultez storage/logs/laravel.log pour les erreurs\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")\n\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
