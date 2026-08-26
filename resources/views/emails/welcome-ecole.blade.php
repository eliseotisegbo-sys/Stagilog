<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { max-width: 150px; }
        .credentials { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 12px 24px; 
                  text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL">
            <h2>Bienvenue sur STAGILOG</h2>
        </div>
        
        <p>Bonjour <strong>{{ $ecole->nom_ecole }}</strong>,</p>
        
        <p>Votre compte école a été créé avec succès sur la plateforme STAGILOG.</p>
        
        <div class="credentials">
            <h3>Vos identifiants de connexion :</h3>
            <p><strong>Nom d'utilisateur :</strong> {{ $username }}</p>
            <p><strong>Mot de passe :</strong> {{ $password }}</p>
        </div>
        
        <p>Vous pouvez vous connecter dès maintenant :</p>
        
        <center>
            <a href="{{ route('login.ecole') }}" class="button">Se connecter</a>
        </center>
        
        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe 
            lors de votre première connexion.
        </p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Technology Forever Group SARL</p>
            <p>Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
