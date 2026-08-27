<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Décision concernant votre dossier de stage - STAGILOG</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #FFF5F5; margin: 0; padding: 30px 20px; color: #1E293B; }
        .wrapper { max-width: 620px; margin: 0 auto; }
        .container { background: #FFFFFF; border-radius: 24px; overflow: hidden; border: 1px solid #FECACA; box-shadow: 0 20px 60px rgba(220, 38, 38, 0.06); }
        .header { background: linear-gradient(135deg, #991B1B 0%, #DC2626 60%, #EF4444 100%); padding: 44px 36px; text-align: center; color: white; position: relative; overflow: hidden; }
        .header::before { content: ''; position: absolute; top: -30px; right: -30px; width: 160px; height: 160px; background: rgba(255,255,255,0.04); border-radius: 50%; }
        .alert-circle { width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; border: 2px solid rgba(255,255,255,0.25); }
        .header h1 { font-size: 26px; font-weight: 800; color: white; margin-bottom: 8px; }
        .header p { font-size: 12px; color: #FEE2E2; text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .content { padding: 40px 36px; }
        .greeting { font-size: 16px; line-height: 1.7; color: #334155; margin-bottom: 28px; }
        .greeting strong { color: #0D1B4B; }
        .section-title { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #6B7AA1; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
        .section-title::before { content: ''; width: 20px; height: 2px; background: #DC2626; border-radius: 2px; }
        .motif-box { background: #FEF2F2; border: 1.5px solid #FECACA; border-left: 4px solid #DC2626; border-radius: 16px; padding: 22px 24px; margin-bottom: 28px; }
        .motif-label { font-size: 11px; font-weight: 800; color: #991B1B; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .motif-text { font-size: 14px; color: #7F1D1D; line-height: 1.6; font-style: italic; }
        .card-details { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 20px; padding: 24px; margin-bottom: 28px; position: relative; overflow: hidden; }
        .card-details::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #DC2626, #F87171); }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px dashed #E2E8F0; gap: 12px; }
        .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
        .detail-label { color: #64748B; font-weight: 600; font-size: 13px; flex-shrink: 0; }
        .detail-value { color: #0D1B4B; font-weight: 800; font-size: 13px; text-align: right; }
        .detail-value.code { font-family: 'Courier New', monospace; color: #1B3A8C; background: #EEF4FF; padding: 4px 10px; border-radius: 8px; border: 1px solid #BFDBFE; }
        .cta-center { text-align: center; margin: 28px 0; }
        .cta-btn { display: inline-block; background: linear-gradient(135deg, #1B3A8C 0%, #0D1B4B 100%); color: white !important; text-decoration: none; padding: 16px 36px; border-radius: 14px; font-size: 14px; font-weight: 800; letter-spacing: 0.3px; box-shadow: 0 8px 25px rgba(27, 58, 140, 0.25); }
        .info-text { font-size: 13px; color: #475569; line-height: 1.7; margin-top: 20px; }
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
                <div class="alert-circle">
                    <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h1>Dossier Non Retenu</h1>
                <p>Décision — Technology Forever Group SARL</p>
            </div>

            <div class="content">
                <p class="greeting">
                    Madame, Monsieur,<br><br>
                    Nous vous informons qu'après un examen attentif, le dossier de demande de stage soumis par <strong>{{ $dossier->ecole->nom_ecole ?? 'votre établissement' }}</strong> n'a malheureusement pas pu être retenu pour la période demandée.<br><br>
                    Nous comprenons que cette décision peut être décevante et nous vous remercions de la confiance que vous nous accordez.
                </p>

                <div class="section-title">Motif de la décision</div>

                <div class="motif-box">
                    <div class="motif-label">Motif communiqué par la direction :</div>
                    <div class="motif-text">« {{ $motif }} »</div>
                </div>

                <div class="section-title">Récapitulatif du dossier</div>

                <div class="card-details">
                    <div class="detail-row">
                        <span class="detail-label">Référence :</span>
                        <span class="detail-value code">{{ $dossier->code_dossier ?? ($dossier->ecole->sigle ?? 'STG') . '-' . ($dossier->created_at ? $dossier->created_at->format('dmYHi') : '') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Établissement :</span>
                        <span class="detail-value">{{ $dossier->ecole->nom_ecole ?? 'N/A' }}</span>
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

                <div class="cta-center">
                    <a href="{{ route('login.ecole') }}" class="cta-btn">Accéder à mon Espace École</a>
                </div>

                <p class="info-text">
                    Vous pouvez soumettre un nouveau dossier rectifié ou contacter le service technique de TFG SARL pour toute information complémentaire à <a href="mailto:stagilogtfg@gmail.com" style="color: #1B3A8C;">stagilogtfg@gmail.com</a>. Nous restons disponibles pour vous accompagner dans vos démarches.
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
