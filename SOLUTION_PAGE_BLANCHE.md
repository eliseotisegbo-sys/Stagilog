# ✅ SOLUTION : Page Blanche - MySQL non démarré

**Problème identifié :** Le serveur MySQL n'est pas démarré

**Erreur exacte :**
```
SQLSTATE[HY000] [2002] Aucune connexion n'a pu être établie car l'ordinateur 
cible l'a expressément refusée (Connection: mysql, Host: 127.0.0.1, Port: 3306)
```

---

## 🔧 SOLUTION : Démarrer MySQL

### Option 1 : Via XAMPP (Recommandé)

1. **Ouvrir XAMPP Control Panel**
   - Chercher "XAMPP" dans le menu Démarrer
   - Ou aller dans `C:\xampp\xampp-control.exe`

2. **Démarrer MySQL**
   - Cliquer sur le bouton **"Start"** à côté de "MySQL"
   - Attendre que le statut devienne vert

3. **Vérifier que MySQL tourne**
   - Le bouton devrait dire "Stop" (au lieu de "Start")
   - Le fond devrait être vert

### Option 2 : Via WAMP (si vous utilisez WAMP)

1. **Lancer WAMP**
   - Icône WAMP dans la barre des tâches
   - Clic gauche sur l'icône

2. **Vérifier les services**
   - L'icône doit être **verte**
   - Si elle est **orange** ou **rouge**, clic gauche → "Start All Services"

### Option 3 : Via MySQL Service (Windows)

1. **Ouvrir les Services Windows**
   - Appuyer sur `Windows + R`
   - Taper `services.msc`
   - Appuyer sur Entrée

2. **Trouver MySQL**
   - Chercher "MySQL" ou "MySQL80" dans la liste
   - Clic droit → "Démarrer"

### Option 4 : Via ligne de commande (Admin)

```cmd
net start MySQL
```

Ou si c'est MySQL 8.0 :
```cmd
net start MySQL80
```

---

## ✅ APRÈS AVOIR DÉMARRÉ MYSQL

### Étape 1 : Vérifier que MySQL fonctionne

```bash
php artisan migrate:status
```

Si vous voyez la liste des migrations, MySQL fonctionne ! ✅

### Étape 2 : Redémarrer le serveur Laravel

```bash
php artisan serve
```

### Étape 3 : Tester l'application

Ouvrir : `http://localhost:8000`

---

## 🔍 SI MYSQL NE DÉMARRE PAS

### Problème : Port 3306 déjà utilisé

**Symptôme :** MySQL ne démarre pas, erreur de port

**Solution :**

1. **Vérifier quel programme utilise le port 3306**
   ```cmd
   netstat -ano | findstr :3306
   ```

2. **Si un autre programme utilise le port :**
   - Option A : Arrêter ce programme
   - Option B : Changer le port MySQL dans XAMPP/WAMP

### Problème : Service MySQL n'existe pas

**Solution :** Réinstaller XAMPP ou WAMP

---

## 📋 CHECKLIST DE VÉRIFICATION

Avant de redémarrer l'application, vérifiez :

- [ ] XAMPP/WAMP est installé
- [ ] MySQL est démarré (bouton "Stop" visible, fond vert)
- [ ] Le fichier `.env` contient les bonnes informations :
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=stagilog
  DB_USERNAME=root
  DB_PASSWORD=
  ```
- [ ] La base de données `stagilog` existe

---

## 🗄️ CRÉER LA BASE DE DONNÉES SI NÉCESSAIRE

Si la base de données `stagilog` n'existe pas :

### Via phpMyAdmin

1. Ouvrir `http://localhost/phpmyadmin`
2. Cliquer sur "Nouvelle base de données"
3. Nom : `stagilog`
4. Interclassement : `utf8mb4_unicode_ci`
5. Cliquer sur "Créer"

### Via ligne de commande

```bash
mysql -u root -p
CREATE DATABASE stagilog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

Puis exécuter les migrations :
```bash
php artisan migrate
```

---

## 🎯 RÉSUMÉ RAPIDE

1. ✅ **Démarrer XAMPP → Start MySQL**
2. ✅ **Vérifier** : `php artisan migrate:status`
3. ✅ **Démarrer Laravel** : `php artisan serve`
4. ✅ **Ouvrir** : `http://localhost:8000`

---

**C'EST TOUT ! Le problème vient uniquement de MySQL qui n'est pas démarré.**

---

*Document créé le 24 août 2026*  
*STAGILOG - Technology Forever Group SARL*
