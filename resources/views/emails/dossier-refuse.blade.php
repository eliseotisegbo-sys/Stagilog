<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Décision concernant votre dossier de stage - STAGILOG</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #F8FAFC; margin: 0; padding: 20px; color: #1E293B; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 10px 25px rgba(13, 27, 75, 0.05); }
        .header { background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%); padding: 36px 30px; text-align: center; color: white; }
        .header h1 { margin: 10px 0 0 0; font-size: 24px; font-weight: 800; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #FEE2E2; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        .content { padding: 36px 30px; }
        .intro { font-size: 15px; line-height: 1.6; color: #334155; margin-bottom: 20px; }
        .motif-box { background: #FEF2F2; border-left: 4px solid #DC2626; border-radius: 12px; padding: 18px; margin: 20px 0; }
        .motif-title { font-size: 12px; font-weight: 800; color: #991B1B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        .motif-text { font-size: 14px; color: #7F1D1D; line-height: 1.5; font-style: italic; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 16px; padding: 18px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .detail-label { color: #64748B; font-weight: 600; }
        .detail-value { color: #0D1B4B; font-weight: 800; text-align: right; }
        .cta-btn { display: inline-block; background: #1B3A8C; color: white; text-decoration: none; padding: 14px 28px; border-radius: 14px; font-size: 13px; font-weight: bold; margin-top: 20px; text-align: center; }
        .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 20px 30px; text-align: center; font-size: 11px; color: #94A3B8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dossier Non Retenu</h1>
            <p>Décision de la direction technique TFG SARL</p>
        </div>

        <div class="content">
            <p class="intro">
                Bonjour <strong>{{ $dossier->ecole->nom_ecole ?? 'Établissement Partenaire' }}</strong>,<br><br>
                Nous vous informons qu'après examen attentif, le dossier de stage déposé pour votre promotion n'a malheureusement pas pu être retenu pour la période demandée.
            </p>

            <div class="motif-box">
                <div class="motif-title">Motif de la décision :</div>
                <div class="motif-text">"{{ $motif }}"</div>
            </div>

            <div class="card-details">
                <div class="detail-row">
                    <span class="detail-label">Référence :</span>
                    <span class="detail-value" style="font-family: monospace;">{{ $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Filière :</span>
                    <span class="detail-value">{{ $dossier->filiere }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Période :</span>
                    <span class="detail-value">{{ $dossier->datedebut ? $dossier->datedebut->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }} au {{ $dossier->datefin ? $dossier->datefin->locale('fr')->isoFormat('ddd. D MMMM YYYY') : '-' }}</span>
                </div>
            </div>

            <p style="font-size: 13px; color: #475569; line-height: 1.5;">
                Vous pouvez soumettre un nouveau dossier rectifié ou contacter le service technique pour toute information complémentaire.
            </p>

            <center>
                <a href="{{ route('login.ecole') }}" class="cta-btn">Consulter mon Espace École</a>
            </center>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} <strong>TFG SARL &bull; STAGILOG</strong>. Tous droits réservés.
        </div>
    </div>
</body>
</html>
