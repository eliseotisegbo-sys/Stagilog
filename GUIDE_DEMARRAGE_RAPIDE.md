# 🚀 GUIDE DE DÉMARRAGE RAPIDE - STAGILOG

**Date :** 24 août 2026  
**Version :** 1.0  
**Statut :** ✅ Application fonctionnelle

---

## 📌 LIENS D'ACCÈS RAPIDE

### 🌐 Page d'accueil
```
http://localhost:8000
```

### 🏫 Connexion ÉCOLES
```
http://localhost:8000/auth/ecole/login
```
**Comptes de test :**
- Email : `admin@ucad.edu.sn` | Mot de passe : `password123`
- Email : `admin@ugb.edu.sn` | Mot de passe : `password123`

### 👑 Connexion ADMIN
```
http://localhost:8000/auth/admin/login
```
**Compte par défaut :**
- Email : `admin@tfg-sarl.com`
- Mot de passe : `Admin@2026`

⚠️ **IMPORTANT :** À la première connexion admin, vous devrez **obligatoirement** changer le mot de passe.

### 📧 Test d'envoi d'email (Développement uniquement)
```
http://localhost:8000/test-email
```
Cette route teste l'envoi d'emails et affiche les informations de configuration.

---

## 🚀 DÉMARRER L'APPLICATION

### Étape 1 : Ouvrir un terminal
```bash
cd C:\Stagilog\stagilog
```

### Étape 2 : Démarrer le serveur
```bash
php artisan serve
```

Vous verrez :
```
Starting Laravel development server: http://127.0.0.1:8000
```

### Étape 3 : Ouvrir votre navigateur
Allez sur : **http://localhost:8000**

---

## 📧 CONFIGURATION DES EMAILS

### 📊 État actuel

**Configuration :** `MAIL_MAILER=log`  
**Statut :** ✅ Fonctionnel en mode LOG

Les emails sont actuellement enregistrés dans :
```
C:\Stagilog\stagilog\storage\logs\laravel.log
```

C'est parfait pour le **développement** ! Aucun email n'est envoyé réellement.

---

## 🔧 CONFIGURER L'ENVOI RÉEL D'EMAILS

### Option 1 : 🧪 MAILTRAP (Recommandé pour les tests)

**Idéal pour :** Développement et tests  
**Coût :** Gratuit  
**Avantage :** Intercepte les emails sans les envoyer vraiment

#### Étapes :

1. **Créer un compte Mailtrap**
   - Aller sur : https://mailtrap.io
   - S'inscrire gratuitement
   - Créer une inbox

2. **Récupérer les credentials**
   - Dans Mailtrap, aller dans votre inbox
   - Copier les informations SMTP

3. **Modifier le fichier `.env`**
   
   Ouvrir `C:\Stagilog\stagilog\.env` et modifier :

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=votre_username_mailtrap
   MAIL_PASSWORD=votre_password_mailtrap
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@stagilog.com"
   MAIL_FROM_NAME="STAGILOG - TFG SARL"
   ```

4. **Redémarrer le serveur**
   ```bash
   php artisan config:clear
   php artisan serve
   ```

5. **Tester l'envoi**
   - Aller sur : http://localhost:8000/test-email
   - Vérifier dans votre inbox Mailtrap

---

### Option 2 : 📬 GMAIL (Pour vrais emails)

**Idéal pour :** Production, emails réels  
**Coût :** Gratuit (jusqu'à 500 emails/jour)  
**Avantage :** Emails réellement envoyés

#### Étapes :

1. **Créer/Utiliser un compte Gmail**
   - Exemple : `stagilog.tfg@gmail.com`

2. **Activer la validation en 2 étapes**
   - Aller sur : https://myaccount.google.com/security
   - Activer "Validation en deux étapes"

3. **Générer un App Password**
   - Aller sur : https://myaccount.google.com/apppasswords
   - Créer un mot de passe d'application
   - Copier le code de 16 caractères

4. **Modifier le fichier `.env`**

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=stagilog.tfg@gmail.com
   MAIL_PASSWORD=votre_app_password_16_caracteres
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="stagilog.tfg@gmail.com"
   MAIL_FROM_NAME="STAGILOG - TFG SARL"
   ```

5. **Redémarrer et tester**
   ```bash
   php artisan config:clear
   php artisan serve
   ```
   
   Tester sur : http://localhost:8000/test-email

---

### Option 3 : 🏢 SERVEUR SMTP PROFESSIONNEL

**Idéal pour :** Production avec domaine personnalisé  
**Exemple :** noreply@tfg-sarl.com

#### Informations nécessaires :

Vous devrez obtenir ces informations de votre hébergeur/fournisseur d'emails :

1. **Hôte SMTP** : `smtp.votre-domaine.com`
2. **Port SMTP** : `587` (TLS) ou `465` (SSL)
3. **Nom d'utilisateur** : `votre-email@tfg-sarl.com`
4. **Mot de passe** : Votre mot de passe email
5. **Chiffrement** : `tls` ou `ssl`

#### Configuration `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-domaine.com
MAIL_PORT=587
MAIL_USERNAME=noreply@tfg-sarl.com
MAIL_PASSWORD=votre_mot_de_passe
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tfg-sarl.com"
MAIL_FROM_NAME="STAGILOG - TFG SARL"
```

---

## 🧪 TESTER L'ENVOI D'EMAILS

### Méthode 1 : Via la route de test

1. Démarrer le serveur : `php artisan serve`
2. Ouvrir : http://localhost:8000/test-email
3. Vérifier la réponse JSON :

**Si succès :**
```json
{
  "success": true,
  "message": "✅ Email envoyé avec succès !",
  "details": {
    "destinataire": "test@example.com",
    "nom_ecole": "Université Cheikh Anta Diop",
    "mail_driver": "smtp",
    "from": "noreply@stagilog.com"
  }
}
```

**Si erreur :**
```json
{
  "success": false,
  "message": "❌ Erreur lors de l'envoi de l'email",
  "error": "Message d'erreur détaillé"
}
```

### Méthode 2 : Via Tinker

```bash
php artisan tinker
```

Puis dans Tinker :

```php
use App\Mail\WelcomeEcoleMail;
use Illuminate\Support\Facades\Mail;

$ecole = (object) [
    'nom_ecole' => 'Test École',
    'mail' => 'test@example.com'
];

$credentials = [
    'email' => 'admin@test.com',
    'password' => 'TestPassword123!'
];

Mail::to($ecole->mail)->send(new WelcomeEcoleMail($ecole, $credentials));

echo "Email envoyé !";
```

---

## 📋 CHECKLIST DE VÉRIFICATION EMAIL

Avant de configurer les emails réels, vérifiez :

- [ ] J'ai accès à un compte email (Gmail, SMTP professionnel, etc.)
- [ ] J'ai les credentials SMTP (hôte, port, username, password)
- [ ] J'ai modifié le fichier `.env` correctement
- [ ] J'ai redémarré le serveur après modification
- [ ] J'ai testé avec http://localhost:8000/test-email
- [ ] Les emails arrivent bien (Mailtrap/Gmail/Boîte mail)

---

## ❓ DÉPANNAGE EMAILS

### Problème : "Connection refused"

**Cause :** Mauvais hôte ou port SMTP

**Solution :**
- Vérifier `MAIL_HOST` et `MAIL_PORT` dans `.env`
- Vérifier que votre pare-feu n'bloque pas le port
- Tester avec Mailtrap d'abord

### Problème : "Authentication failed"

**Cause :** Mauvais username ou password

**Solution :**
- Vérifier `MAIL_USERNAME` et `MAIL_PASSWORD`
- Pour Gmail, utiliser un App Password, pas le mot de passe normal
- Vérifier que la validation en 2 étapes est activée (Gmail)

### Problème : "Email envoyé mais pas reçu"

**Cause :** Email dans les spams ou mauvaise adresse

**Solution :**
- Vérifier le dossier spam/courrier indésirable
- Vérifier `MAIL_FROM_ADDRESS` est valide
- Utiliser Mailtrap pour les tests

### Problème : Les modifications `.env` ne sont pas prises en compte

**Solution :**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📧 QUAND LES EMAILS SONT-ILS ENVOYÉS ?

Dans l'application STAGILOG, les emails sont envoyés dans ces cas :

### ✅ Implémenté actuellement :

1. **Création d'une école par l'admin**
   - Email : Bienvenue avec identifiants de connexion
   - Template : `WelcomeEcoleMail`

### 🔜 À implémenter (prochaines phases) :

2. **Validation d'un dossier**
   - Email à l'école confirmant la validation

3. **Refus d'un dossier**
   - Email à l'école avec raison du refus

4. **Dépôt d'un rapport**
   - Email à l'école notifiant qu'un rapport est disponible

5. **Réinitialisation de mot de passe**
   - Email avec lien de réinitialisation

---

## 🎯 RÉSUMÉ : QUE CHOISIR ?

| Option | Pour qui ? | Coût | Complexité | Emails réels |
|--------|-----------|------|------------|--------------|
| **LOG** (actuel) | Développement | Gratuit | ⭐ Facile | ❌ Non |
| **Mailtrap** | Tests | Gratuit | ⭐⭐ Facile | ❌ Non (interceptés) |
| **Gmail** | Production | Gratuit | ⭐⭐⭐ Moyen | ✅ Oui |
| **SMTP Pro** | Production | Variable | ⭐⭐⭐⭐ Avancé | ✅ Oui |

### Ma recommandation :

1. **Développement/Tests** : Garder `MAIL_MAILER=log` ou utiliser Mailtrap
2. **Production** : Utiliser Gmail ou SMTP professionnel

---

## 📞 BESOIN D'AIDE ?

Si vous avez besoin de configurer les emails, dites-moi :

1. **Quelle option vous intéresse ?**
   - Mailtrap (tests)
   - Gmail (emails réels)
   - SMTP professionnel

2. **Avez-vous déjà un compte email ?**
   - Si oui, lequel ?
   - Si non, je vous guide pour en créer un

3. **Pour quel usage ?**
   - Tests/Développement
   - Production

---

## 🔗 LIENS UTILES

- Documentation Laravel Mail : https://laravel.com/docs/11.x/mail
- Mailtrap : https://mailtrap.io
- Configuration Gmail : https://support.google.com/accounts/answer/185833
- Vérifier votre configuration SMTP : https://www.gmass.co/smtp-test

---

**📝 Note :** Cette route de test (`/test-email`) n'est disponible qu'en environnement `local` et sera automatiquement désactivée en production.

---

*Document généré le 24 août 2026*  
*STAGILOG - Technology Forever Group SARL*
