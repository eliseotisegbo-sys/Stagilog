-- ============================================
-- FICHIER DE DONNÉES DE TEST
-- À exécuter après migration_database.sql
-- ============================================

-- Vider les tables si elles existent (pour réinitialiser les tests)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE etudiants;
TRUNCATE TABLE dossiers;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- INSERTION DES DOSSIERS DE STAGE
-- ============================================

-- Dossier 1 : UCAD - Génie Informatique
INSERT INTO dossiers (id_dossier, annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole) 
VALUES (1, '2025-2026', 'Génie Informatique', NULL, '2026-01-15', '2026-06-30', 
    (SELECT id_ecole FROM ecoles WHERE nom_ecole LIKE '%Cheikh Anta Diop%' LIMIT 1));

-- Dossier 2 : UGB - Réseaux et Télécommunications
INSERT INTO dossiers (id_dossier, annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole) 
VALUES (2, '2025-2026', 'Réseaux et Télécommunications', NULL, '2026-02-01', '2026-07-31', 
    (SELECT id_ecole FROM ecoles WHERE nom_ecole LIKE '%Gaston Berger%' LIMIT 1));

-- Dossier 3 : ESP - Génie Logiciel
INSERT INTO dossiers (id_dossier, annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole) 
VALUES (3, '2025-2026', 'Génie Logiciel', NULL, '2026-01-20', '2026-06-20', 
    (SELECT id_ecole FROM ecoles WHERE nom_ecole LIKE '%ESP%' LIMIT 1));

-- Dossier 4 : UCAD - Master Intelligence Artificielle
INSERT INTO dossiers (id_dossier, annee_academique, filiere, lettredemande, datedebut, datefin, id_ecole) 
VALUES (4, '2025-2026', 'Master Intelligence Artificielle', NULL, '2026-03-01', '2026-08-31', 
    (SELECT id_ecole FROM ecoles WHERE nom_ecole LIKE '%Cheikh Anta Diop%' LIMIT 1));

-- ============================================
-- INSERTION DES ÉTUDIANTS
-- ============================================

-- Étudiants pour le dossier 1 (UCAD - Génie Informatique)
INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, rapport, id_dossier) 
VALUES 
    ('Diop', 'Amadou', 'amadou.diop@ucad.edu.sn', 'cv_amadou_diop.pdf', NULL, 1),
    ('Ndiaye', 'Fatou', 'fatou.ndiaye@ucad.edu.sn', 'cv_fatou_ndiaye.pdf', 'rapport_fatou_ndiaye.pdf', 1),
    ('Sy', 'Mamadou', 'mamadou.sy@ucad.edu.sn', 'cv_mamadou_sy.pdf', NULL, 1);

-- Étudiants pour le dossier 2 (UGB - Réseaux et Télécommunications)
INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, rapport, id_dossier) 
VALUES 
    ('Sow', 'Moussa', 'moussa.sow@ugb.edu.sn', 'cv_moussa_sow.pdf', NULL, 2),
    ('Ba', 'Aissatou', 'aissatou.ba@ugb.edu.sn', 'cv_aissatou_ba.pdf', 'rapport_aissatou_ba.pdf', 2);

-- Étudiants pour le dossier 3 (ESP - Génie Logiciel)
INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, rapport, id_dossier) 
VALUES 
    ('Fall', 'Ibrahima', 'ibrahima.fall@esp.edu.sn', 'cv_ibrahima_fall.pdf', 'rapport_ibrahima_fall.pdf', 3),
    ('Sarr', 'Mariama', 'mariama.sarr@esp.edu.sn', 'cv_mariama_sarr.pdf', NULL, 3),
    ('Cisse', 'Ousmane', 'ousmane.cisse@esp.edu.sn', 'cv_ousmane_cisse.pdf', 'rapport_ousmane_cisse.pdf', 3),
    ('Diouf', 'Awa', 'awa.diouf@esp.edu.sn', 'cv_awa_diouf.pdf', NULL, 3);

-- Étudiants pour le dossier 4 (UCAD - Master IA)
INSERT INTO etudiants (nom_etudiant, prenom_etudiant, email_etu, cv, rapport, id_dossier) 
VALUES 
    ('Ndour', 'Cheikh', 'cheikh.ndour@ucad.edu.sn', 'cv_cheikh_ndour.pdf', NULL, 4),
    ('Gueye', 'Maimouna', 'maimouna.gueye@ucad.edu.sn', 'cv_maimouna_gueye.pdf', NULL, 4);

-- ============================================
-- VÉRIFICATION DES DONNÉES INSÉRÉES
-- ============================================

-- Compter les dossiers
SELECT '=== DOSSIERS INSÉRÉS ===' AS Info;
SELECT COUNT(*) AS 'Nombre de dossiers' FROM dossiers;

-- Compter les étudiants
SELECT '=== ÉTUDIANTS INSÉRÉS ===' AS Info;
SELECT COUNT(*) AS 'Nombre d\'étudiants' FROM etudiants;

-- Afficher les dossiers avec leur école
SELECT 
    d.id_dossier,
    e.nom_ecole,
    d.filiere,
    d.annee_academique,
    COUNT(et.id_etudiant) AS 'Nombre étudiants'
FROM dossiers d
INNER JOIN ecoles e ON e.id_ecole = d.id_ecole
LEFT JOIN etudiants et ON et.id_dossier = d.id_dossier
GROUP BY d.id_dossier, e.nom_ecole, d.filiere, d.annee_academique
ORDER BY d.id_dossier;

-- Afficher les étudiants avec leur dossier
SELECT 
    et.id_etudiant,
    et.nom_etudiant,
    et.prenom_etudiant,
    et.email_etu,
    d.filiere,
    e.nom_ecole,
    CASE WHEN et.rapport IS NOT NULL THEN 'Oui' ELSE 'Non' END AS 'Rapport déposé'
FROM etudiants et
INNER JOIN dossiers d ON d.id_dossier = et.id_dossier
INNER JOIN ecoles e ON e.id_ecole = d.id_ecole
ORDER BY et.id_etudiant;

-- ============================================
-- INFORMATIONS POUR LES TESTS
-- ============================================

SELECT '=== INFORMATIONS POUR TESTER ===' AS Info;

SELECT '--- Pour tester le dépôt de rapport ---' AS 'Test 1';
SELECT 
    CONCAT('Nom: ', nom_etudiant, ', Prénom: ', prenom_etudiant) AS 'Étudiant SANS rapport',
    email_etu AS 'Email'
FROM etudiants 
WHERE rapport IS NULL
LIMIT 3;

SELECT '--- Pour consulter les rapports déjà déposés ---' AS 'Test 2';
SELECT 
    CONCAT('Nom: ', nom_etudiant, ', Prénom: ', prenom_etudiant) AS 'Étudiant AVEC rapport',
    email_etu AS 'Email'
FROM etudiants 
WHERE rapport IS NOT NULL
LIMIT 3;
