<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Période Officielle de Dépôt des Dossiers de Stage - TFG SARL</title>
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
        .header h1 { font-size: 24px; font-weight: 800; color: white; margin-bottom: 8px; }
        .header p { font-size: 12px; color: #93C5FD; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .content { padding: 40px 36px; }
        .greeting { font-size: 15px; line-height: 1.7; color: #334155; margin-bottom: 24px; }
        .greeting strong { color: #0D1B4B; }
        .periode-card { background: linear-gradient(135deg, #EEF4FF 0%, #F8FAFC 100%); border: 2px solid #BFDBFE; border-radius: 20px; padding: 24px; margin-bottom: 28px; text-align: center; position: relative; overflow: hidden; }
        .periode-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #1B3A8C, #E8001D); }
        .periode-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #1B3A8C; margin-bottom: 8px; }
        .periode-dates { font-size: 20px; font-weight: 900; color: #0D1B4B; margin-bottom: 6px; }
        .periode-status { display: inline-block; background: #DCFCE7; color: #166534; font-size: 11px; font-weight: 800; padding: 4px 12px; rounded-full: 9999px; border-radius: 9999px; text-transform: uppercase; }
        .instructions-box { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin-bottom: 24px; font-size: 14px; line-height: 1.6; color: #475569; }
        .instructions-box h4 { font-size: 12px; font-weight: 800; text-transform: uppercase; color: #0D1B4B; margin-bottom: 8px; }
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
                <h1>Ouverture des Dépôts de Candidatures</h1>
                <p>Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Madame, Monsieur la Direction de <strong>{{ $ecole->nom_ecole }}</strong>,<br><br>
                    La direction générale de <strong>Technology Forever Group (TFG SARL)</strong> a le plaisir de vous notifier l'ouverture officielle de la campagne de dépôt des dossiers de demande de stage pour vos étudiants.
                </p>

                <!-- Carte Période Officielle -->
                <div class="periode-card">
                    <div class="periode-title">Période Officielle de Dépôt</div>
                    <div class="periode-dates">Du {{ $dateDebut }} au {{ $dateFin }}</div>
                    <span class="periode-status">Session Ouverte</span>
                </div>

                <div class="instructions-box">
                    <h4>Modalités de transmission</h4>
                    <p>
                        Les dépôts des dossiers de candidatures de vos étudiants (contenant leurs informations et leurs curriculum vitae) doivent être effectués exclusivement en ligne via votre portail partenaire <strong>STAGILOG</strong> durant cette fenêtre de dates.
                    </p>
                    @if(!empty($messagePersonnalise))
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px dashed #CBD5E1; color: #1E293B;">
                        <strong>Note particulière :</strong> {{ $messagePersonnalise }}
                    </div>
                    @endif
                </div>

                <div class="cta-center">
                    <a href="{{ route('login.ecole') }}" class="cta-btn">Accéder à STAGILOG pour Déposer</a>
                </div>

                <div class="divider"></div>

                <div class="notice">
                    <strong>⚠️ Important :</strong> Conformément à notre charte de traitement des stages, aucun dossier ne pourra être soumis ou accepté en dehors de cette période définie. Nous vous invitons à finaliser la soumission de vos promotions dans les délais impartis.
                </div>

                <p style="font-size: 13px; color: #64748B; margin-top: 24px; line-height: 1.6;">
                    Pour toute question ou information complémentaire, le service administratif de TFG SARL reste à votre entière disposition à l'adresse <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C; font-weight: bold;">stagilogtfg@gmail.com</a>.
                </p>
            </div>

            <div class="footer">
                <div class="footer-brand">Technology Forever Group SARL &bull; STAGILOG</div>
                <div class="footer-text">
                    Direction des Ressources Humaines &bull; TFG SARL<br>
                    Support : <a href="mailto:stagilogtfg@gmail.com">stagilogtfg@gmail.com</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
