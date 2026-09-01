<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information Candidature de Stage - TFG SARL</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 30px 20px; color: #1E293B; }
        .wrapper { max-width: 620px; margin: 0 auto; }
        .container { background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 20px 60px rgba(15, 23, 42, 0.06); }
        .header { background: linear-gradient(135deg, #7F1D1D 0%, #B91C1C 60%, #991B1B 100%); padding: 40px 36px; text-align: center; color: white; position: relative; overflow: hidden; }
        .alert-circle { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; border: 2px solid rgba(255,255,255,0.25); }
        .header h1 { font-size: 22px; font-weight: 800; color: white; margin-bottom: 6px; }
        .header p { font-size: 11px; color: #FCA5A5; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; }
        .content { padding: 36px; }
        .greeting { font-size: 15px; line-height: 1.7; color: #334155; margin-bottom: 24px; }
        .greeting strong { color: #0D1B4B; }
        .motif-box { background: #FEF2F2; border: 1.5px solid #FECACA; border-left: 4px solid #DC2626; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; }
        .motif-label { font-size: 11px; font-weight: 800; color: #991B1B; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .motif-text { font-size: 14px; color: #7F1D1D; font-weight: 700; line-height: 1.5; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 20px 24px; margin-bottom: 24px; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E2E8F0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748B; font-weight: 600; font-size: 13px; }
        .detail-value { color: #0D1B4B; font-weight: 800; font-size: 13px; }
        .detail-value.code { font-family: 'Courier New', monospace; color: #1B3A8C; background: #EEF4FF; padding: 3px 8px; border-radius: 6px; }
        .notice-box { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 14px; padding: 16px 20px; margin-bottom: 24px; font-size: 13px; color: #1E40AF; line-height: 1.6; }
        .notice-box strong { color: #1E3A8A; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 36px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="alert-circle">
                    <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1>Décision Concernant Votre Candidature</h1>
                <p>Notification Officielle — Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Bonjour <strong>{{ $etudiant->prenom_etudiant }} {{ $etudiant->nom_etudiant }}</strong>,<br><br>
                    Nous vous informons qu'après examen attentif des dossiers présentés par <strong>{{ $dossier->ecole->nom_ecole ?? 'votre établissement' }}</strong> dans le cadre du dossier <strong>{{ $dossier->code_dossier }}</strong>, votre candidature individuelle pour un stage n'a malheureusement pas pu être retenue pour cette session.
                </p>

                <!-- Motif du Refus -->
                <div class="motif-box">
                    <div class="motif-label">Motif de la décision</div>
                    <div class="motif-text">{{ $motifRefus }}</div>
                </div>

                <!-- Détails du dossier -->
                <div class="card-details">
                    <div class="detail-row">
                        <span class="detail-label">Référence du Dossier :</span>
                        <span class="detail-value code">{{ $dossier->code_dossier }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Établissement :</span>
                        <span class="detail-value">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Filière concernée :</span>
                        <span class="detail-value">{{ $dossier->filiere }}</span>
                    </div>
                </div>

                <!-- Recommandation Rapprochement École -->
                <div class="notice-box">
                    <strong>ℹ️ Démarche à suivre :</strong><br>
                    Pour toute information complémentaire ou réorientation de votre demande, nous vous invitons à <strong>vous rapprocher directement de la direction des stages ou du secrétariat de votre établissement</strong> ({{ $dossier->ecole->nom_ecole ?? 'votre école' }}).
                </div>

                <p style="font-size: 13px; color: #64748B; line-height: 1.6;">
                    Nous vous remercions pour l'intérêt que vous portez à Technology Forever Group et vous souhaitons une excellente continuation dans votre parcours académique et professionnel.
                </p>
            </div>

            <div class="footer">
                <strong>Technology Forever Group SARL &bull; STAGILOG</strong><br>
                Direction des Ressources Humaines &bull; Support : <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C; text-decoration: none;">stagilogtfg@gmail.com</a>
            </div>
        </div>
    </div>
</body>
</html>
