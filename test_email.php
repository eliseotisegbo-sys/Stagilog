<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;

echo "=== TEST ENVOI EMAIL STAGILOG ===\n\n";

try {
    // Test 1: Email simple
    echo "Test 1: Envoi email simple...\n";
    Mail::raw('Ceci est un test depuis STAGILOG', function ($message) {
        $message->to('stagilogtfg@gmail.com')
                ->subject('Test Email STAGILOG - ' . date('H:i:s'));
    });
    echo "✅ Email simple envoyé avec succès!\n\n";

    // Test 2: Email avec template PasswordResetCodeMail
    echo "Test 2: Envoi email avec template PasswordResetCodeMail...\n";
    $user = User::first();
    if ($user) {
        $code = '123456';
        Mail::to('stagilogtfg@gmail.com')->send(new PasswordResetCodeMail($code, $user));
        echo "✅ Email avec template envoyé avec succès!\n";
        echo "   Destinataire: stagilogtfg@gmail.com\n";
        echo "   Code test: {$code}\n";
        echo "   Utilisateur: {$user->name}\n\n";
    } else {
        echo "⚠️ Aucun utilisateur trouvé dans la base\n\n";
    }

    echo "=== TOUS LES TESTS RÉUSSIS ===\n";
    echo "Vérifiez la boîte email: stagilogtfg@gmail.com\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
