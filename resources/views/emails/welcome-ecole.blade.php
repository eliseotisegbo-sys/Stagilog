<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue sur STAGILOG - Vos accès officiel</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 10px 25px rgba(13, 27, 75, 0.05); }
        .header { background: linear-gradient(135deg, #0D1B4B 0%, #1B3A8C 100%); padding: 36px 30px; text-align: center; color: white; }
        .header h1 { margin: 10px 0 0 0; font-size: 24px; font-weight: 800; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #93C5FD; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        .content { padding: 36px 30px; }
        .intro { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 20px; }
        .credentials-card { background: #F8FAFC; border: 2px solid #E2E8F0; border-radius: 18px; padding: 22px; margin: 24px 0; }
        .credential-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #EDF2F7; font-size: 14px; }
        .credential-row:last-child { border-bottom: none; }
        .label { color: #64748B; font-weight: 600; }
        .value { color: #0D1B4B; font-weight: 800; font-family: monospace; font-size: 15px; }
        .cta-btn { display: inline-block; background: #1B3A8C; color: white; text-decoration: none; padding: 14px 28px; border-radius: 14px; font-size: 13px; font-weight: bold; margin-top: 20px; text-align: center; }
        .notice { font-size: 12px; color: #64748B; margin-top: 24px; line-height: 1.5; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 12px; padding: 12px; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 30px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue sur STAGILOG</h1>
            <p>Espace Partenaire Établissement</p>
        </div>

        <div class="content">
            <p class="intro">
                Bonjour <strong>{{ $ecole->nom_ecole }}</strong>,<br><br>
                Votre compte d'accès officiel à la plateforme <strong>STAGILOG</strong> a été créé et validé par <strong>TFG SARL</strong>. Vous pouvez dès à présent vous connecter pour soumettre vos dossiers d'étudiants et suivre leurs évaluations.
            </p>

            <div class="credentials-card">
                <div class="credential-row">
                    <span class="label">Identifiant / Email :</span>
                    <span class="value">{{ $username }}</span>
                </div>
                <div class="credential-row">
                    <span class="label">Mot de passe temporaire :</span>
                    <span class="value" style="color: #E8001D;">{{ $password }}</span>
                </div>
            </div>

            <center>
                <a href="{{ route('login.ecole') }}" class="cta-btn">Accéder à la Plateforme</a>
            </center>

            <div class="notice">
                <strong>Sécurité :</strong> Lors de votre première connexion, un code de confirmation vous sera transmis par email. Vous pourrez également personnaliser votre mot de passe dans les paramètres de votre espace.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>Technology Forever Group SARL (TFG)</strong> &bull; STAGILOG.<br>
            Contact support : <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C;">stagilogtfg@gmail.com</a>
        </div>
    </div>
</body>
</html>
