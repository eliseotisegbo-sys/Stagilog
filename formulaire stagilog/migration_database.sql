-- ============================================
-- MIGRATION DE LA BASE DE DONNÉES STAGILOG
-- Restructuration pour conformité au diagramme
-- ============================================

-- Remarque : La table 'ecoles' existe déjà dans Laravel
-- Nous créons seulement les tables dossiers et etudiants

-- 1. Supprimer l'ancienne table dossier si elle existe
DROP TABLE IF EXISTS dossiers_old;
DROP TABLE IF EXISTS dossier;

-- 2. Créer la nouvelle table dossiers (sans les informations élève)
CREATE TABLE IF NOT EXISTS dossiers (
    id_dossier INT PRIMARY KEY AUTO_INCREMENT,
    annee_academique VARCHAR(50) NOT NULL,
    filiere VARCHAR(255) NOT NULL,
    lettredemande VARCHAR(255) NULL,
    datedebut DATE NOT NULL,
    datefin DATE NOT NULL,
    id_ecole BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ecole) REFERENCES ecoles(id_ecole) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- 3. Supprimer l'ancienne table etudiants si elle existe (pour recréer proprement)
DROP TABLE IF EXISTS etudiants;

-- 4. Créer la table etudiants
CREATE TABLE IF NOT EXISTS etudiants (
    id_etudiant INT PRIMARY KEY AUTO_INCREMENT,
    nom_etudiant VARCHAR(255) NOT NULL,
    prenom_etudiant VARCHAR(255) NOT NULL,
    email_etu VARCHAR(255) NOT NULL UNIQUE,
    cv VARCHAR(255) NOT NULL,
    rapport VARCHAR(255) NULL,
    id_dossier INT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_dossier) REFERENCES dossiers(id_dossier) ON UPDATE CASCADE ON DELETE CASCADE
);

-- 5. Vérification
SELECT 'Migration terminee avec succes !' AS Status;
SELECT TABLE_NAME, TABLE_ROWS 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'stagilog' 
AND TABLE_NAME IN ('ecoles', 'dossiers', 'etudiants');
