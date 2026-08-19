<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAGILOG - Système de gestion des stages</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 1000px;
            width: 100%;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        .card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        .card-icon {
            font-size: 4em;
            margin-bottom: 15px;
        }
        .card h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.4em;
        }
        .card p {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 15px 35px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s;
            font-weight: bold;
            font-size: 1.05em;
        }
        .btn:hover {
            background: #764ba2;
            transform: scale(1.05);
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 30px 0;
            border-radius: 5px;
        }
        .info-box h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 STAGILOG</h1>
            <p>Système de gestion des demandes de stage</p>
        </div>


            <div class="cards">
                <div class="card">
                    <h2>Créer une demande</h2>
                    <p>Créez un dossier de stage et ajoutez tous vos étudiants en même temps</p>
                    <a href="creer_dossier_complet.php" class="btn">Accéder</a>
                </div>

                <div class="card">
                    <h2>Dépôt de rapport</h2>
                    <p>Consultez votre dossier et déposez votre rapport de stage</p>
                    <a href="depot_rapport.php" class="btn">Accéder</a>
                </div>

                <div class="card">
                    <h2>Statuts des dossiers</h2>
                    <p>Consultez l'état et les détails de tous vos dossiers</p>
                    <a href="statuts_dossiers.php" class="btn">Accéder</a>
                </div>

                <div class="card">
                    <h2>Administration</h2>
                    <p>Gérez les demandes : consultez, validez ou refusez les dossiers</p>
                    <a href="administration.php" class="btn">Accéder</a>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
