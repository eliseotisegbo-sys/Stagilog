<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Période de Stage Réajustée - STAGILOG</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 30px 20px; color: #1E293B; }
        .wrapper { max-width: 620px; margin: 0 auto; }
        .container { background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.06); }
        .header { background: linear-gradient(135deg, #1B3A8C 0%, #0D1B4B 100%); padding: 40px 36px; text-align: center; color: white; position: relative; overflow: hidden; }
        .alert-circle { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; border: 2px solid rgba(255,255,255,0.25); }
        .header h1 { font-size: 24px; font-weight: 800; color: white; margin-bottom: 6px; }
        .header p { font-size: 12px; color: #93C5FD; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; }
        .content { padding: 36px; }
        .greeting { font-size: 15px; line-height: 1.7; color: #334155; margin-bottom: 24px; }
        .greeting strong { color: #0D1B4B; }
        .date-box { background: #FEF3C7; border: 1.5px solid #FCD34D; border-left: 4px solid #D97706; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; }
        .date-label { font-size: 11px; font-weight: 800; color: #92400E; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .date-text { font-size: 15px; color: #78350F; font-weight: 800; line-height: 1.5; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 20px 24px; margin-bottom: 24px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E2E8F0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748B; font-weight: 600; font-size: 13px; }
        .detail-value { color: #0D1B4B; font-weight: 800; font-size: 13px; }
        .detail-value.code { font-family: 'Courier New', monospace; color: #1B3A8C; background: #EEF4FF; padding: 3px 8px; border-radius: 6px; }
        .btn-group { display: flex; flex-direction: column; gap: 12px; margin: 28px 0; }
        .cta-btn { display: block; text-align: center; background: #1B3A8C; color: white !important; text-decoration: none; padding: 15px 24px; border-radius: 14px; font-size: 13px; font-weight: 800; }
        .cta-btn-danger { display: block; text-align: center; background: #FEF2F2; color: #DC2626 !important; border: 1.5px solid #FECACA; text-decoration: none; padding: 15px 24px; border-radius: 14px; font-size: 13px; font-weight: 800; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 36px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="alert-circle">
                    <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1>Nouvelle Période de Stage</h1>
                <p>Mise sous réserve — Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Bonjour,<br><br>
                    Nous vous informons que la période de stage pour le dossier <strong>{{ $dossier->code_dossier }}</strong> (Établissement : <strong>{{ $dossier->ecole->nom_ecole ?? 'École' }}</strong>) a été ajustée par l'administrateur <strong>{{ $adminName }}</strong>.
                </p>

                <div class="date-box">
                    <div class="date-label">🗓️ Nouvelle Période Proposée par TFG SARL</div>
                    <div class="date-text">
                        Du {{ $dossier->datedebut ? $dossier->datedebut->format('d/m/Y') : '-' }} au {{ $dossier->datefin ? $dossier->datefin->format('d/m/Y') : '-' }}
                    </div>
                </div>

                <div class="card-details">
                    <div class="detail-row">
                        <span class="detail-label">Code Dossier</span>
                        <span class="detail-value code">{{ $dossier->code_dossier }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Filière / Spécialité</span>
                        <span class="detail-value">{{ $dossier->filiere }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Administrateur</span>
                        <span class="detail-value">{{ $adminName }}</span>
                    </div>
                </div>

                <p style="font-size: 13px; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                    Si cette nouvelle date de stage ne convient pas aux disponibilités de votre établissement ou des stagiaires, vous avez la possibilité de décliner cette proposition ci-dessous. Le dossier actuel sera classé comme refusé et vous serez automatiquement redirigé vers le formulaire pour réintroduire une nouvelle demande avec vos dates souhaitées.
                </p>

                <div class="btn-group">
                    <a href="{{ url('/ecole/dossiers/' . $dossier->id_dossier) }}" class="cta-btn">
                        Consulter mon Dossier dans STAGILOG
                    </a>
                    <a href="{{ url('/ecole/dossiers/' . $dossier->id_dossier . '/refuser-nouvelle-date') }}" class="cta-btn-danger">
                        ❌ Refuser cette date &amp; Créer un Nouveau Dossier
                    </a>
                </div>
            </div>

            <div class="footer">
                <p><strong>STAGILOG</strong> — Plateforme de Gestion des Stages TFG SARL</p>
                <p>Cotonou, République du Bénin • Support : stagilogtfg@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>
