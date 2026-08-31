<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification de la Période de Stage - STAGILOG</title>
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
        .date-box { background: #EEF4FF; border: 1.5px solid #BFDBFE; border-left: 4px solid #1B3A8C; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; }
        .date-label { font-size: 11px; font-weight: 800; color: #1B3A8C; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .date-text { font-size: 16px; color: #0D1B4B; font-weight: 800; line-height: 1.5; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 20px 24px; margin-bottom: 24px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E2E8F0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748B; font-weight: 600; font-size: 13px; }
        .detail-value { color: #0D1B4B; font-weight: 800; font-size: 13px; }
        .detail-value.code { font-family: 'Courier New', monospace; color: #1B3A8C; background: #EEF4FF; padding: 3px 8px; border-radius: 6px; }
        .notice-box { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #92400E; line-height: 1.6; }
        .notice-box strong { color: #78350F; }
        .cta-btn { display: block; text-align: center; background: #1B3A8C; color: white !important; text-decoration: none; padding: 15px 24px; border-radius: 14px; font-size: 13px; font-weight: 800; margin: 24px 0; }
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
                <h1>Période de Stage Réajustée</h1>
                <p>Notification Officielle — Technology Forever Group SARL</p>
            </div>

            <div class="content">
                @if($recipientType === 'etudiant')
                <p class="greeting">
                    Bonjour <strong>{{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</strong>,<br><br>
                    Nous vous informons que votre période de stage rattachée au dossier <strong>{{ $dossier->code_dossier }}</strong> (Établissement : <strong>{{ $dossier->ecole->nom_ecole ?? 'Votre école' }}</strong>) a été planifiée / réajustée par l'administration de TFG SARL.
                </p>
                @else
                <p class="greeting">
                    Madame, Monsieur de l'administration de <strong>{{ $dossier->ecole->nom_ecole ?? 'l\'établissement' }}</strong>,<br><br>
                    Nous vous informons que la période de stage pour l'étudiant(e) <strong>{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</strong> dans le dossier <strong>{{ $dossier->code_dossier }}</strong> a été ajustée par l'administration TFG SARL.
                </p>
                @endif

                <div class="date-box">
                    <div class="date-label">Période de Stage Attribuée</div>
                    <div class="date-text">
                        Du {{ $etudiant->datedebut_stage ? $etudiant->datedebut_stage->format('d/m/Y') : ($dossier->datedebut ? $dossier->datedebut->format('d/m/Y') : '-') }} 
                        au {{ $etudiant->datefin_stage ? $etudiant->datefin_stage->format('d/m/Y') : ($dossier->datefin ? $dossier->datefin->format('d/m/Y') : '-') }}
                    </div>
                </div>

                <div class="card-details">
                    <div class="detail-row">
                        <span class="detail-label">Étudiant</span>
                        <span class="detail-value">{{ $etudiant->nom_etudiant }} {{ $etudiant->prenom_etudiant }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Code Dossier</span>
                        <span class="detail-value code">{{ $dossier->code_dossier }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Filière / Cycle</span>
                        <span class="detail-value">{{ $dossier->filiere }} ({{ $dossier->cycle->nom_cycle ?? 'Standard' }})</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Établissement</span>
                        <span class="detail-value">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</span>
                    </div>
                </div>

                @if($recipientType === 'etudiant')
                <div class="notice-box">
                    <strong>Information Importante :</strong><br>
                    Si cette date ne vous convient pas ou entre en conflit avec votre calendrier académique, nous vous prions de <strong>vous rapprocher immédiatement de votre école / université</strong> afin de faire remonter votre demande d'aménagement.
                </div>
                @else
                <div class="notice-box">
                    <strong>Information à l'Établissement :</strong><br>
                    Si cette période de stage nécessite un réaménagement ou un ajustement pour cet étudiant, nous vous remercions de <strong>bien vouloir contacter directement la direction de TFG SARL</strong> à <a href="mailto:stagilogtfg@gmail.com" style="color:#1B3A8C; font-weight:700;">stagilogtfg@gmail.com</a>.
                </div>
                @endif
            </div>

            <div class="footer">
                <p><strong>STAGILOG</strong> — Plateforme de Gestion des Stages TFG SARL</p>
                <p>Cotonou, République du Bénin • Support : stagilogtfg@gmail.com</p>
            </div>
        </div>
    </div>
</body>
</html>
