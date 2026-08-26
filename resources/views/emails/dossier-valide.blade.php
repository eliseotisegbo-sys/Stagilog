<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation Officielle de Dossier de Stage - STAGILOG</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 10px 25px rgba(13, 27, 75, 0.05); }
        .header { background: linear-gradient(135deg, #059669 0%, #10B981 100%); padding: 36px 30px; text-align: center; color: white; }
        .header h1 { margin: 10px 0 0 0; font-size: 24px; font-weight: 800; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #D1FAE5; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        .content { padding: 36px 30px; }
        .intro { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 20px; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 20px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #EDF2F7; font-size: 13px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748B; font-weight: 600; }
        .detail-value { color: #0D1B4B; font-weight: 800; text-align: right; }
        .cta-btn { display: inline-block; background: #1B3A8C; color: white; text-decoration: none; padding: 14px 28px; border-radius: 14px; font-size: 13px; font-weight: bold; margin-top: 20px; text-align: center; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 30px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dossier de Stage Validé !</h1>
            <p>Notification Officielle TFG SARL</p>
        </div>

        <div class="content">
            <p class="intro">
                Bonjour <strong>{{ $dossier->ecole->nom_ecole ?? 'Établissement Partenaire' }}</strong>,<br><br>
                Nous avons le plaisir de vous informer que le dossier de stage déposé pour votre promotion a été <strong>officiellement validé</strong> par la direction technique de <strong>TFG SARL</strong>.
            </p>

            <div class="card-details">
                <div class="detail-row">
                    <span class="detail-label">Référence Dossier :</span>
                    <span class="detail-value" style="font-family: monospace; color: #1B3A8C;">{{ $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Filière / Domaine :</span>
                    <span class="detail-value">{{ $dossier->filiere }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cycle Académique :</span>
                    <span class="detail-value">{{ $dossier->cycle->nom_cycle ?? 'Standard' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nombre d'Étudiants :</span>
                    <span class="detail-value">{{ $dossier->etudiants->count() }} candidat(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Période de Stage :</span>
                    <span class="detail-value">
                        {{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }} au {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}
                    </span>
                </div>
            </div>

            <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                Les étudiants peuvent démarrer leur stage dès la période convenue. Vous pourrez consulter les rapports et fiches d'évaluation directement depuis votre espace partenaire.
            </p>

            <center>
                <a href="{{ route('login.ecole') }}" class="cta-btn">Accéder à mon Espace École</a>
            </center>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>TFG SARL &bull; STAGILOG</strong>. Tous droits réservés.
        </div>
    </div>
</body>
</html>
