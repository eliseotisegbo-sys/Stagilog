<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur STAGILOG - Vos identifiants de connexion</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F0F4FF; margin: 0; padding: 30px 20px; color: #1E293B; }
        .wrapper { max-width: 620px; margin: 0 auto; }
        .container { background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 20px 60px rgba(13, 27, 75, 0.08); }
        .header { background: linear-gradient(135deg, #0D1B4B 0%, #1B3A8C 60%, #2548B8 100%); padding: 44px 36px; text-align: center; color: white; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -30px; right: -30px; width: 160px; height: 160px; background: rgba(255,255,255,0.04); border-radius: 50%; }
        .header::after { content: ''; position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; background: rgba(232,0,29,0.08); border-radius: 50%; }
        .logo-box { display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.12); border-radius: 14px; padding: 8px 18px; margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.15); }
        .logo-text { font-size: 22px; font-weight: 900; color: white; letter-spacing: 1px; }
        .logo-dot { width: 8px; height: 8px; background: #E8001D; border-radius: 50%; margin-left: 8px; display: inline-block; }
        .header h1 { font-size: 26px; font-weight: 800; color: white; margin-bottom: 8px; }
        .header p { font-size: 12px; color: #93C5FD; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .content { padding: 40px 36px; }
        .greeting { font-size: 16px; line-height: 1.7; color: #334155; margin-bottom: 28px; }
        .greeting strong { color: #0D1B4B; }
        .section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #6B7AA1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .section-title::before { content: ''; width: 20px; height: 2px; background: #1B3A8C; border-radius: 2px; }
        .credentials-card { background: linear-gradient(135deg, #F8FAFC 0%, #EEF4FF 100%); border: 2px solid #E2E8F0; border-radius: 20px; padding: 28px; margin-bottom: 28px; position: relative; overflow: hidden; }
        .credentials-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #1B3A8C, #E8001D); }
        .credential-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px dashed #E2E8F0; gap: 12px; }
        .credential-row:last-child { border-bottom: none; padding-bottom: 0; }
        .credential-label { color: #64748B; font-weight: 600; font-size: 13px; flex-shrink: 0; }
        .credential-value { color: #0D1B4B; font-weight: 800; font-family: 'Courier New', monospace; font-size: 15px; word-break: break-all; text-align: right; }
        .credential-value.password { color: #E8001D; background: #FFF5F5; padding: 4px 10px; border-radius: 8px; border: 1px solid #FECACA; }
        .credential-value.email-val { color: #1B3A8C; background: #EEF4FF; padding: 4px 10px; border-radius: 8px; border: 1px solid #BFDBFE; }
        .credential-value.url-val { color: #1B3A8C; font-size: 13px; }
        .cta-center { text-align: center; margin: 28px 0; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #1B3A8C 0%, #0D1B4B 100%); color: white !important; text-decoration: none; padding: 16px 36px; border-radius: 14px; font-size: 14px; font-weight: 800; letter-spacing: 0.3px; box-shadow: 0 8px 25px rgba(27, 58, 140, 0.3); }
        .notice { font-size: 13px; color: #475569; background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 14px; padding: 16px 20px; line-height: 1.6; margin-top: 24px; }
        .notice strong { color: #92400E; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 24px 36px; text-align: center; }
        .footer-brand { font-size: 13px; color: #0D1B4B; font-weight: 800; margin-bottom: 6px; }
        .footer-text { font-size: 11px; color: #94A3B8; line-height: 1.5; }
        .footer-text a { color: #1B3A8C; text-decoration: none; }
        .divider { height: 1px; background: linear-gradient(90deg, transparent, #E2E8F0, transparent); margin: 24px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="logo-box">
                    <span class="logo-text">STAGILOG</span>
                    <span class="logo-dot"></span>
                </div>
                <h1>Bienvenue sur STAGILOG</h1>
                <p>Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Madame, Monsieur,<br><br>
                    Nous avons le plaisir de vous informer que l'établissement <strong>{{ $ecole->nom_ecole }}</strong> a été enregistré avec succès sur la plateforme <strong>STAGILOG</strong>, dédiée à la gestion des demandes de stage de Technology Forever Group SARL.<br><br>
                    Vous trouverez ci-dessous vos identifiants de connexion, qui vous permettront d'accéder à votre espace personnel afin de soumettre vos demandes de stage et de suivre leur traitement.
                </p>

                <div class="section-title">Vos identifiants de connexion</div>

                <div class="credentials-card">
                    <div class="credential-row">
                        <span class="credential-label">Adresse de connexion :</span>
                        <span class="credential-value url-val">{{ $urlConnexion ?? route('login.ecole') }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Identifiant (email) :</span>
                        <span class="credential-value email-val">{{ $credentials['email'] }}</span>
                    </div>
                    <div class="credential-row">
                        <span class="credential-label">Mot de passe provisoire :</span>
                        <span class="credential-value password">{{ $credentials['password'] }}</span>
                    </div>
                </div>

                <div class="cta-center">
                    <a href="{{ $urlConnexion ?? route('login.ecole') }}" class="cta-btn">Accéder à la Plateforme STAGILOG</a>
                </div>

                <div class="divider"></div>

                <div class="notice">
                    <strong>⚠️ Sécurité :</strong> Lors de votre première connexion, un code de vérification vous sera envoyé par email pour confirmer votre identité. Nous vous recommandons de modifier votre mot de passe provisoire dès votre première connexion depuis les <em>Paramètres</em> de votre espace.
                </div>

                <p style="font-size: 13px; color: #64748B; margin-top: 24px; line-height: 1.6;">
                    Pour toute question ou assistance technique, veuillez contacter notre support à l'adresse <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C;">stagilogtfg@gmail.com</a>.
                </p>
            </div>

            <div class="footer">
                <div class="footer-brand">Technology Forever Group SARL &bull; STAGILOG</div>
                <div class="footer-text">
                    &copy; {{ date('Y') }} TFG SARL. Tous droits réservés.<br>
                    Support : <a href="mailto:stagilogtfg@gmail.com">stagilogtfg@gmail.com</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
