<?php
session_start();
require 'config.php';

$erreur = '';
$succes = '';
if (isset($_SESSION['erreur'])) {
    $erreur = $_SESSION['erreur'];
    unset($_SESSION['erreur']);
}
if (isset($_SESSION['succes'])) {
    $succes = $_SESSION['succes'];
    unset($_SESSION['succes']);
}

$stmt = $pdo->query("SELECT id_ecole, nom_ecole FROM ecoles ORDER BY nom_ecole");
$ecoles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une demande de stage</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-bottom: 10px; }
        .menu { margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px; }
        .menu a { margin-right: 15px; text-decoration: none; color: #007bff; }
        .menu a:hover { text-decoration: underline; }
        .error { color: #721c24; background: #f8d7da; border: 1px solid #f5c6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .success { color: #155724; background: #d4edda; border: 1px solid #c3e6cb; padding: 12px; margin: 15px 0; border-radius: 5px; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 12px; margin: 15px 0; }
        .section { margin: 30px 0; padding: 20px; border: 2px solid #007bff; border-radius: 8px; }
        .section h2 { color: #007bff; margin-bottom: 15px; font-size: 1.3em; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #007bff; }
        .etudiant-bloc { background: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 5px; border-left: 4px solid #28a745; position: relative; }
        .etudiant-bloc h3 { color: #28a745; margin-bottom: 10px; }
        .btn-supprimer { position: absolute; top: 10px; right: 10px; background: #dc3545; color: white; border: none; padding: 5px 15px; border-radius: 3px; cursor: pointer; }
        .btn-supprimer:hover { background: #c82333; }
        .btn { padding: 12px 30px; margin: 10px 5px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0056b3; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-secondary { background: #6c757d; color: white; }
        .btn-secondary:hover { background: #545b62; }
        .actions { text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="menu">
            <a href="index.php">🏠 Accueil</a>
            <a href="creer_dossier_complet.php">📝 Créer un dossier</a>
            <a href="depot_rapport.php">📄 Dépôt de rapport</a>
            <a href="statuts_dossiers.php">📊 Statuts des dossiers</a>
            <a href="administration.php">🏛️ Administration</a>
        </div>

        <h1>📋 Créer une demande de stage</h1>
        
        <?php if ($erreur): ?>
            <div class="error">❌ <?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>
        
        <?php if ($succes): ?>
            <div class="success">✅ <?= htmlspecialchars($succes) ?></div>
        <?php endif; ?>

        <div class="info">
            <strong>ℹ️ Information :</strong> Remplissez les informations du dossier puis ajoutez tous les étudiants concernés. La demande sera envoyée en attente de validation.
        </div>

        <form action="traiter_dossier_complet.php" method="POST" enctype="multipart/form-data" id="formDossier">
            
            <!-- SECTION DOSSIER -->
            <div class="section">
                <h2>1️⃣ Informations du dossier</h2>
                
                <label for="id_ecole">École * :</label>
                <select name="id_ecole" id="id_ecole" required>
                    <option value="">-- Sélectionner une école --</option>
                    <?php foreach ($ecoles as $ecole): ?>
                        <option value="<?= $ecole['id_ecole'] ?>">
                            <?= htmlspecialchars($ecole['nom_ecole']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="annee_academique">Année académique * :</label>
                <input type="text" name="annee_academique" id="annee_academique" placeholder="ex: 2025-2026" required>

                <label for="filiere">Filière * :</label>
                <input type="text" name="filiere" id="filiere" placeholder="ex: Génie Informatique" required>

                <label for="datedebut">Date de début * :</label>
                <input type="date" name="datedebut" id="datedebut" required>

                <label for="datefin">Date de fin * :</label>
                <input type="date" name="datefin" id="datefin" required>

                <label for="lettredemande">Lettre de demande (PDF - optionnel) :</label>
                <input type="file" name="lettredemande" id="lettredemande" accept="application/pdf">
            </div>

            <!-- SECTION ÉTUDIANTS -->
            <div class="section">
                <h2>2️⃣ Étudiants du dossier</h2>
                
                <div id="etudiants-container">
                    <!-- Étudiant 1 par défaut -->
                    <div class="etudiant-bloc" data-index="1">
                        <h3>Étudiant 1</h3>
                        
                        <label>Nom * :</label>
                        <input type="text" name="etudiants[1][nom]" required>
                        
                        <label>Prénom * :</label>
                        <input type="text" name="etudiants[1][prenom]" required>
                        
                        <label>Email * :</label>
                        <input type="email" name="etudiants[1][email]" placeholder="exemple@ecole.edu.sn" required>
                        
                        <label>CV (PDF) * :</label>
                        <input type="file" name="etudiants[1][cv]" accept="application/pdf" required>
                    </div>
                </div>

                <button type="button" class="btn btn-success" onclick="ajouterEtudiant()">
                    ➕ Ajouter un autre étudiant
                </button>
            </div>

            <!-- ACTIONS -->
            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    📤 Envoyer la demande
                </button>
                <button type="reset" class="btn btn-secondary">
                    🔄 Réinitialiser
                </button>
            </div>
        </form>
    </div>

    <script>
        let etudiantIndex = 1;

        function ajouterEtudiant() {
            etudiantIndex++;
            const container = document.getElementById('etudiants-container');
            
            const div = document.createElement('div');
            div.className = 'etudiant-bloc';
            div.setAttribute('data-index', etudiantIndex);
            
            div.innerHTML = `
                <button type="button" class="btn-supprimer" onclick="supprimerEtudiant(this)">❌ Supprimer</button>
                <h3>Étudiant ${etudiantIndex}</h3>
                
                <label>Nom * :</label>
                <input type="text" name="etudiants[${etudiantIndex}][nom]" required>
                
                <label>Prénom * :</label>
                <input type="text" name="etudiants[${etudiantIndex}][prenom]" required>
                
                <label>Email * :</label>
                <input type="email" name="etudiants[${etudiantIndex}][email]" placeholder="exemple@ecole.edu.sn" required>
                
                <label>CV (PDF) * :</label>
                <input type="file" name="etudiants[${etudiantIndex}][cv]" accept="application/pdf" required>
            `;
            
            container.appendChild(div);
        }

        function supprimerEtudiant(btn) {
            const bloc = btn.closest('.etudiant-bloc');
            const container = document.getElementById('etudiants-container');
            
            // Ne pas supprimer si c'est le seul étudiant
            if (container.children.length <= 1) {
                alert('Vous devez avoir au moins un étudiant dans le dossier.');
                return;
            }
            
            bloc.remove();
            
            // Renuméroter les étudiants
            const blocs = container.querySelectorAll('.etudiant-bloc');
            blocs.forEach((b, index) => {
                b.querySelector('h3').textContent = `Étudiant ${index + 1}`;
            });
        }

        // Validation des dates
        document.getElementById('datefin').addEventListener('change', function() {
            const debut = document.getElementById('datedebut').value;
            const fin = this.value;
            
            if (debut && fin && fin < debut) {
                alert('La date de fin ne peut pas être antérieure à la date de début.');
                this.value = '';
            }
        });
    </script>
</body>
</html>
