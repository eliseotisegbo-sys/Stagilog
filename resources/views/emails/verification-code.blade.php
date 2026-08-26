<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code de Sécurité STAGILOG</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
        .container { max-width: 580px; margin: 0 auto; background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 10px 25px rgba(13, 27, 75, 0.05); }
        .header { background: linear-gradient(135deg, #0D1B4B 0%, #1B3A8C 100%); padding: 36px 30px; text-align: center; color: white; position: relative; }
        .header h1 { margin: 10px 0 0 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #93C5FD; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        .badge-tfg { display: inline-block; padding: 4px 12px; background: rgba(232, 0, 29, 0.2); border: 1px solid rgba(232, 0, 29, 0.4); border-radius: 20px; color: #FF6B6B; font-size: 11px; font-weight: bold; margin-bottom: 8px; }
        .content { padding: 36px 30px; }
        .intro { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 24px; }
        .otp-box { background: #F1F5F9; border: 2px dashed #1B3A8C; border-radius: 18px; padding: 24px; text-align: center; margin: 24px 0; }
        .otp-title { font-size: 12px; text-transform: uppercase; font-weight: 700; color: #64748B; letter-spacing: 1px; margin-bottom: 8px; }
        .otp-code { font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 900; letter-spacing: 12px; color: #1B3A8C; margin: 0; padding-left: 12px; }
        .otp-validity { font-size: 11px; color: #E8001D; font-weight: 600; margin-top: 10px; }
        .alert-card { background: #EFF6FF; border-left: 4px solid #1B3A8C; border-radius: 8px; padding: 14px 18px; font-size: 12px; line-height: 1.5; color: #1E40AF; margin-top: 24px; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 30px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="badge-tfg">STAGILOG &bull; SÉCURITÉ</span>
            <h1>Code de Vérification</h1>
            <p>Vérification de Connexion</p>
        </div>

        <div class="content">
            <p class="intro">
                Bonjour <strong>{{ $user->name ?? 'Utilisateur' }}</strong>,<br><br>
                Une tentative de connexion à votre espace sécurisé <strong>STAGILOG</strong> vient d'être initiée. Utilisez le code de vérification ci-dessous pour confirmer votre identité :
            </p>

            <div class="otp-box">
                <div class="otp-title">Votre Code à 6 Chiffres</div>
                <div class="otp-code">{{ $code }}</div>
                <div class="otp-validity">Ce code est valable pendant 10 minutes</div>
            </div>

            <div class="alert-card">
                <strong>Attention :</strong> Ne partagez jamais ce code avec une tierce personne. Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>TFG SARL &bull; STAGILOG</strong>. Tous droits réservés.<br>
            Plateforme de gestion et suivi des stages académiques et professionnels.
        </div>
    </div>
</body>
</html>
