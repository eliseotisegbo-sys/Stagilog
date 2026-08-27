<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de votre Stage - Technology Forever Group SARL</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F0FFF4; margin: 0; padding: 30px 20px; color: #1E293B; }
        .wrapper { max-width: 620px; margin: 0 auto; }
        .container { background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #D1FAE5; box-shadow: 0 20px 60px rgba(5, 150, 105, 0.08); }
        .header { background: linear-gradient(135deg, #047857 0%, #059669 60%, #10B981 100%); padding: 44px 36px; text-align: center; color: white; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -30px; right: -30px; width: 160px; height: 160px; background: rgba(255,255,255,0.05); border-radius: 50%; }
        .checkmark-circle { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 2px solid rgba(255,255,255,0.3); }
        .header h1 { font-size: 26px; font-weight: 800; color: white; margin-bottom: 8px; }
        .header p { font-size: 12px; color: #D1FAE5; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .content { padding: 40px 36px; }
        .greeting { font-size: 16px; line-height: 1.7; color: #334155; margin-bottom: 28px; }
        .greeting strong { color: #0D1B4B; }
        .success-banner { background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border: 1.5px solid #6EE7B7; border-radius: 16px; padding: 20px 24px; margin-bottom: 28px; display: flex; align-items: center; gap: 16px; }
        .success-icon { width: 40px; height: 40px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .success-text { font-size: 14px; color: #065F46; font-weight: 600; line-height: 1.5; }
        .section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #6B7AA1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .section-title::before { content: ''; width: 20px; height: 2px; background: #059669; border-radius: 2px; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 24px; margin-bottom: 28px; position: relative; overflow: hidden; }
        .card-details::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #059669, #10B981); }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed #E2E8F0; gap: 12px; }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
        .detail-label { color: #64748B; font-weight: 600; font-size: 13px; flex-shrink: 0; }
        .detail-value { color: #0D1B4B; font-weight: 800; font-size: 13px; text-align: right; }
        .detail-value.period { color: #047857; font-size: 14px; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 24px 36px; text-align: center; }
        .footer-brand { font-size: 13px; color: #0D1B4B; font-weight: 800; margin-bottom: 6px; }
        .footer-text { font-size: 11px; color: #94A3B8; line-height: 1.5; }
        .footer-text a { color: #1B3A8C; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="checkmark-circle">
                    <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1>Félicitations {{ $etudiant->prenom_etudiant }} !</h1>
                <p>Votre stage est officiellement validé — Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Bonjour <strong>{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</strong>,<br><br>
                    Nous avons le plaisir de vous annoncer que votre candidature de stage soumise par <strong>{{ $dossier->ecole->nom_ecole ?? 'votre établissement' }}</strong> a été <strong>officiellement retenue et validée</strong> par la direction technique de <strong>Technology Forever Group SARL (TFG)</strong>.<br><br>
                    Toute l'équipe de TFG SARL se réjouit de vous accueillir pour cette période d'immersion professionnelle.
                </p>

                <div class="success-banner">
                    <div class="success-icon">
                        <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="success-text">
                        Stage validé : vous pouvez démarrer vos activités dès la date de début fixée.
                    </div>
                </div>

                <div class="section-title">Détails de votre stage</div>

                <div class="card-details">
                    <div class="detail-row">
                        <span class="detail-label">Stagiaire :</span>
                        <span class="detail-value">{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Établissement / Université :</span>
                        <span class="detail-value">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Filière / Spécialité :</span>
                        <span class="detail-value">{{ $dossier->filiere }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Niveau d'Étude :</span>
                        <span class="detail-value">{{ $etudiant->niveau_etude ?? $dossier->niveau_etude }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Période Officielle :</span>
                        <span class="detail-value period">
                            {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                            au
                            {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd D MMMM YYYY') : '-' }}
                        </span>
                    </div>
                </div>

                <p style="font-size: 13px; color: #475569; line-height: 1.7;">
                    Veuillez vous rapprocher de la direction de votre établissement pour les formalités administratives complémentaires. Pour toute question ou information pratique, écrivez-nous à <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C; font-weight: bold;">stagilogtfg@gmail.com</a>.
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
