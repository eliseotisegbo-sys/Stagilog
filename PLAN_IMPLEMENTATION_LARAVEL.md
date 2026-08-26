# 📋 PLAN D'IMPLÉMENTATION LARAVEL - STAGILOG

Date : 24 août 2026
Basé sur le nouveau workflow + Design des images

---

## 🎯 OBJECTIFS

Implémenter dans le projet Laravel (`C:\Stagilog\stagilog`) :
1. **Nouveau design de connexion** :
   - Écoles : inspiration de l'image 1 (fond salon, carte blanche)
   - Admin : première inscription à la 1ère connexion, puis email/mot de passe
2. **Page d'accueil** avec logo TFG (sans arrière-plan)
3. **Tables et fonctionnalités** du nouveau workflow
4. **Architecture Laravel complète** (MVC, migrations, seeders, middleware)

---

## 📂 STRUCTURE DU PROJET

```
C:\Stagilog\stagilog\
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── FirstTimeSetupController.php
│   │   │   ├── DossierController.php
│   │   │   ├── EcolesController.php
│   │   │   ├── EtudiantsController.php
│   │   │   ├── FiliereController.php
│   │   │   ├── RapportController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       ├── CheckRole.php
│   │       ├── CheckFirstLogin.php
│   │       └── EnsureEmailVerified.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Ecole.php
│   │   ├── Dossier.php
│   │   ├── Etudiant.php
│   │   ├── Cycle.php
│   │   ├── Filiere.php
│   │   └── EmailHistorique.php
│   ├── Mail/
│   │   ├── WelcomeEcoleMail.php
│   │   └── PasswordResetMail.php
│   └── Notifications/
│       └── EcoleCreatedNotification.php
├── database/
│   ├── migrations/
│   │   ├── [existantes...]
│   │   ├── 2026_08_24_create_cycles_table.php
│   │   ├── 2026_08_24_create_filieres_table.php
│   │   ├── 2026_08_24_add_fields_to_dossiers_table.php
│   │   ├── 2026_08_24_add_fields_to_etudiants_table.php
│   │   ├── 2026_08_24_create_emails_historique_table.php
│   │   ├── 2026_08_24_add_first_login_to_users_table.php
│   │   └── 2026_08_24_add_fields_to_ecoles_table.php
│   └── seeders/
│       ├── CycleSeeder.php
│       ├── FiliereSeeder.php
│       └── AdminSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php
│   │   │   ├── auth.blade.php
│   │   │   └── dashboard.blade.php
│   │   ├── auth/
│   │   │   ├── login-ecole.blade.php
│   │   │   ├── login-admin.blade.php
│   │   │   └── first-time-setup.blade.php
│   │   ├── welcome.blade.php (page d'accueil)
│   │   ├── dashboard/
│   │   │   ├── ecole.blade.php
│   │   │   └── admin.blade.php
│   │   ├── dossiers/
│   │   │   ├── create.blade.php
│   │   │   ├── edit.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── validation.blade.php
│   │   ├── ecoles/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── filieres/
│   │   │   └── index.blade.php
│   │   └── rapports/
│   │       ├── index.blade.php
│   │       └── depot.blade.php
│   └── css/
│       └── app.css (TailwindCSS)
├── public/
│   ├── images/
│   │   ├── logo-tfg.png (extrait de WhatsApp Image 2026-08-24 at 15.26.55.jpeg)
│   │   └── bg-login.jpg (baa6f127adc772b43bbcfe5d8ab17ce0.jpg)
│   └── uploads/
│       ├── cv/
│       ├── contrats/
│       ├── notes/
│       ├── rapports/
│       ├── pv_stages/
│       └── autres/
└── routes/
    └── web.php
```

---

## 🗄️ PHASE 1 : MIGRATIONS & MODÈLES

### 1.1 Créer la migration `cycles`

```bash
php artisan make:migration create_cycles_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->id('id_cycle');
            $table->string('nom_cycle', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
```

### 1.2 Créer la migration `filieres`

```bash
php artisan make:migration create_filieres_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filieres', function (Blueprint $table) {
            $table->id('id_filiere');
            $table->string('nom_filiere');
            $table->text('description')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
```

### 1.3 Modifier la table `dossiers`

```bash
php artisan make:migration add_nouveau_workflow_fields_to_dossiers_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            // Changer lettredemande en note_demande
            $table->renameColumn('lettredemande', 'note_demande');
            
            // Ajouter statut brouillon
            $table->enum('statut_brouillon', ['brouillon', 'soumis'])
                  ->default('brouillon')
                  ->after('statut');
            
            // Ajouter relations cycle et filiere
            $table->unsignedBigInteger('id_cycle')->nullable()->after('filiere');
            $table->unsignedBigInteger('id_filiere')->nullable()->after('id_cycle');
            
            // Ajouter champs supplémentaires
            $table->string('type_stage', 100)->nullable()->after('id_filiere');
            $table->string('niveau_etude', 100)->nullable()->after('type_stage');
            
            // Clés étrangères
            $table->foreign('id_cycle')->references('id_cycle')->on('cycles')->onDelete('set null');
            $table->foreign('id_filiere')->references('id_filiere')->on('filieres')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            $table->dropForeign(['id_cycle']);
            $table->dropForeign(['id_filiere']);
            $table->dropColumn(['statut_brouillon', 'id_cycle', 'id_filiere', 'type_stage', 'niveau_etude']);
            $table->renameColumn('note_demande', 'lettredemande');
        });
    }
};
```

### 1.4 Modifier la table `etudiants`

```bash
php artisan make:migration add_nouveau_workflow_fields_to_etudiants_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->date('date_naissance')->nullable()->after('prenom_etudiant');
            $table->string('niveau_etude', 100)->nullable()->after('date_naissance');
            $table->string('contrat')->nullable()->after('cv');
            $table->json('autres_documents')->nullable()->after('contrat');
            
            // Pour gérer plusieurs types de rapports
            $table->string('pv_stage')->nullable()->after('rapport');
            $table->enum('type_rapport', ['rapport_etudiant', 'pv_stage', 'autre'])->nullable()->after('pv_stage');
        });
    }

    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            $table->dropColumn([
                'date_naissance',
                'niveau_etude',
                'contrat',
                'autres_documents',
                'pv_stage',
                'type_rapport'
            ]);
        });
    }
};
```

### 1.5 Créer la table `emails_historique`

```bash
php artisan make:migration create_emails_historique_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emails_historique', function (Blueprint $table) {
            $table->id('id_email');
            $table->string('destinataire');
            $table->string('sujet');
            $table->text('contenu');
            $table->string('type_email', 50);
            $table->boolean('envoye')->default(false);
            $table->timestamp('date_envoi')->nullable();
            $table->unsignedBigInteger('id_ecole')->nullable();
            $table->timestamps();
            
            $table->foreign('id_ecole')->references('id_ecole')->on('ecoles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emails_historique');
    }
};
```

### 1.6 Ajouter champs à la table `users` (first_login)

```bash
php artisan make:migration add_first_login_to_users_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('first_login')->default(true)->after('password');
            $table->timestamp('first_login_at')->nullable()->after('first_login');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_login', 'first_login_at']);
        });
    }
};
```

### 1.7 Ajouter champs à la table `ecoles`

```bash
php artisan make:migration add_contact_fields_to_ecoles_table
```

```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecoles', function (Blueprint $table) {
            $table->string('email')->nullable()->after('mail');
            $table->string('telephone')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('ecoles', function (Blueprint $table) {
            $table->dropColumn(['email', 'telephone']);
        });
    }
};
```

---

## 🎨 PHASE 2 : SEEDERS

### 2.1 Seeder pour les cycles

```bash
php artisan make:seeder CycleSeeder
```

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CycleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cycles')->insert([
            ['nom_cycle' => 'Licence', 'created_at' => now(), 'updated_at' => now()],
            ['nom_cycle' => 'Master', 'created_at' => now(), 'updated_at' => now()],
            ['nom_cycle' => 'Ingénieur', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
```

### 2.2 Seeder pour les filières

```bash
php artisan make:seeder FiliereSeeder
```

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FiliereSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = [
            ['nom_filiere' => 'Informatique', 'description' => 'Développement logiciel, réseaux, IA', 'actif' => true],
            ['nom_filiere' => 'Génie Civil', 'description' => 'Construction, infrastructures', 'actif' => true],
            ['nom_filiere' => 'Électricité', 'description' => 'Installations électriques, énergie', 'actif' => true],
            ['nom_filiere' => 'Télécommunications', 'description' => 'Réseaux, systèmes de communication', 'actif' => true],
            ['nom_filiere' => 'Commerce', 'description' => 'Marketing, vente, gestion', 'actif' => true],
            ['nom_filiere' => 'Comptabilité', 'description' => 'Finance, gestion comptable', 'actif' => true],
        ];

        foreach ($filieres as $filiere) {
            DB::table('filieres')->insert(array_merge($filiere, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
```

### 2.3 Seeder pour l'admin par défaut

```bash
php artisan make:seeder AdminSeeder
```

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur TFG',
            'email' => 'admin@tfg-sarl.com',
            'password' => Hash::make('Admin@2026'), // À changer à la première connexion
            'role' => 'admin',
            'first_login' => true,
        ]);
    }
}
```

### 2.4 Mettre à jour DatabaseSeeder

```php
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CycleSeeder::class,
            FiliereSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
```

---

## 🏗️ PHASE 3 : MODÈLES ELOQUENT

### 3.1 Modèle Cycle

```bash
php artisan make:model Cycle
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    protected $table = 'cycles';
    protected $primaryKey = 'id_cycle';
    
    protected $fillable = ['nom_cycle'];
    
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_cycle', 'id_cycle');
    }
}
```

### 3.2 Modèle Filiere

```bash
php artisan make:model Filiere
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $table = 'filieres';
    protected $primaryKey = 'id_filiere';
    
    protected $fillable = [
        'nom_filiere',
        'description',
        'actif'
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];
    
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_filiere', 'id_filiere');
    }
    
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
```

### 3.3 Modèle EmailHistorique

```bash
php artisan make:model EmailHistorique
```

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistorique extends Model
{
    protected $table = 'emails_historique';
    protected $primaryKey = 'id_email';
    
    protected $fillable = [
        'destinataire',
        'sujet',
        'contenu',
        'type_email',
        'envoye',
        'date_envoi',
        'id_ecole'
    ];
    
    protected $casts = [
        'envoye' => 'boolean',
        'date_envoi' => 'datetime',
    ];
    
    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'id_ecole', 'id_ecole');
    }
}
```

### 3.4 Mettre à jour les modèles existants

**App\Models\Dossier.php** :

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossiers';
    protected $primaryKey = 'id_dossier';

    protected $fillable = [
        'annee_academique',
        'filiere',
        'note_demande',
        'datedebut',
        'datefin',
        'id_ecole',
        'statut',
        'statut_brouillon',
        'id_cycle',
        'id_filiere',
        'type_stage',
        'niveau_etude',
    ];

    protected $casts = [
        'datedebut' => 'date',
        'datefin' => 'date',
    ];

    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'id_ecole', 'id_ecole');
    }

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'id_dossier', 'id_dossier');
    }
    
    public function cycle()
    {
        return $this->belongsTo(Cycle::class, 'id_cycle', 'id_cycle');
    }
    
    public function filiereRelation()
    {
        return $this->belongsTo(Filiere::class, 'id_filiere', 'id_filiere');
    }
    
    // Scopes
    public function scopeBrouillon($query)
    {
        return $query->where('statut_brouillon', 'brouillon');
    }
    
    public function scopeSoumis($query)
    {
        return $query->where('statut_brouillon', 'soumis');
    }
    
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
    
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }
}
```

**App\Models\Etudiant.php** :

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $table = 'etudiants';
    protected $primaryKey = 'id_etudiant';

    protected $fillable = [
        'nom_etudiant',
        'prenom_etudiant',
        'email_etu',
        'date_naissance',
        'niveau_etude',
        'cv',
        'contrat',
        'autres_documents',
        'rapport',
        'pv_stage',
        'type_rapport',
        'id_dossier',
    ];
    
    protected $casts = [
        'date_naissance' => 'date',
        'autres_documents' => 'array',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }
    
    public function hasRapport()
    {
        return !is_null($this->rapport);
    }
    
    public function hasPvStage()
    {
        return !is_null($this->pv_stage);
    }
}
```

**App\Models\Ecole.php** (renommer depuis ecoles.php) :

```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    protected $table = 'ecoles';
    protected $primaryKey = 'id_ecole';

    protected $fillable = [
        'nom_ecole',
        'adresse_ecole',
        'num_ecole',
        'mail',
        'email',
        'telephone',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_ecole', 'id_ecole');
    }

    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_ecole', 'id_ecole');
    }
    
    public function emailsHistorique()
    {
        return $this->hasMany(EmailHistorique::class, 'id_ecole', 'id_ecole');
    }
}
```

---

## 🎨 PHASE 4 : VUES BLADE (DESIGN)

### 4.1 Page d'accueil (welcome.blade.php)

Utiliser le logo TFG + design inspiré de l'image 1

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STAGILOG - TFG SARL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    
    <!-- Header avec logo -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL" class="h-16">
                <div>
                    <h1 class="text-2xl font-bold text-blue-900">STAGILOG</h1>
                    <p class="text-sm text-gray-600">Technology Forever Group SARL</p>
                </div>
            </div>
            <nav class="space-x-4">
                <a href="#accueil" class="text-gray-700 hover:text-blue-600">Accueil</a>
                <a href="#apropos" class="text-gray-700 hover:text-blue-600">À propos</a>
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Se connecter
                </a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="accueil" class="relative h-screen flex items-center justify-center bg-cover bg-center"
             style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative z-10 text-center text-white">
            <h2 class="text-5xl font-bold mb-4">Bienvenue sur STAGILOG</h2>
            <p class="text-xl mb-8">Plateforme de gestion des stages - TFG SARL</p>
            <div class="space-x-4">
                <a href="{{ route('login.ecole') }}" class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100">
                    Espace École
                </a>
                <a href="{{ route('login.admin') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Espace Admin
                </a>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="apropos" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-12">À propos de TFG SARL</h2>
            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        TFG SARL est une société intervenant dans les différents domaines industriels 
                        de la vie des entreprises. Dotée d'une équipe dynamique de jeunes ingénieurs 
                        de conception et de techniciens avec une forte expérience.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        TFG SARL a pour vocation d'accompagner les différentes entreprises publiques 
                        et privées dans l'optimisation de leur performance opérationnelle en mettant 
                        à leur disposition son savoir-faire et ses meilleures pratiques.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="font-bold text-blue-900 mb-2">💻 Informatique</h3>
                        <p class="text-sm text-gray-600">Développement logiciel</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="font-bold text-blue-900 mb-2">🎨 Infographie</h3>
                        <p class="text-sm text-gray-600">Design & communication</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="font-bold text-blue-900 mb-2">📡 Télécom</h3>
                        <p class="text-sm text-gray-600">Réseaux & systèmes</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="font-bold text-blue-900 mb-2">⚡ Énergie</h3>
                        <p class="text-sm text-gray-600">Électricité & froid</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} Technology Forever Group SARL. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
```

### 4.2 Page de connexion École (auth/login-ecole.blade.php)

Inspirée de l'image 1 : fond avec salon, carte blanche à droite

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion École - STAGILOG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex items-center justify-center bg-cover bg-center"
      style="background-image: url('{{ asset('images/bg-login.jpg') }}');">
    
    <div class="absolute inset-0 bg-black bg-opacity-30"></div>
    
    <div class="relative z-10 w-full max-w-6xl mx-4 flex">
        
        <!-- Partie gauche : Message de bienvenue -->
        <div class="hidden md:block w-1/2 text-white p-12">
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg p-8 border border-white border-opacity-30">
                <h1 class="text-3xl font-bold mb-4">Bonjour !</h1>
                <h2 class="text-4xl font-extrabold mb-6 leading-tight">
                    Bon retour sur votre<br>espace personnel !
                </h2>
                <p class="text-lg">
                    Nous sommes ravis de vous retrouver parmi nous.
                </p>
            </div>
        </div>
        
        <!-- Partie droite : Formulaire -->
        <div class="w-full md:w-1/2 bg-white rounded-2xl shadow-2xl p-10">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-bold text-gray-800 mb-2">Se connecter</h3>
                <p class="text-gray-600">Accédez à votre espace personnel</p>
            </div>
            
            @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
                {{ $errors->first() }}
            </div>
            @endif
            
            <form method="POST" action="{{ route('login.ecole.submit') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                    <input type="email" name="email" id="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Entrez votre email" required>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                    <input type="password" name="password" id="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Entrez votre mot de passe" required>
                </div>
                
                <div class="text-right mb-6">
                    <a href="#" class="text-gray-500 text-sm hover:underline">Mot de passe oublié ?</a>
                </div>
                
                <button type="submit" 
                        class="w-full bg-green-600 text-white py-3 rounded-full font-semibold hover:bg-green-700 transition">
                    Se connecter
                </button>
                
                <div class="text-center my-6 text-gray-500">
                    OU SE CONNECTER AVEC
                </div>
                
                <div class="flex justify-center space-x-4">
                    <button type="button" class="p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <button type="button" class="p-3 bg-black text-white rounded-full hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.05 20.28c-.98.95-2.05.88-3.08.4-1.09-.5-2.08-.48-3.24 0-1.44.62-2.2.44-3.06-.4C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                        </svg>
                    </button>
                    <button type="button" class="p-3 bg-red-600 text-white rounded-full hover:bg-red-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                        </svg>
                    </button>
                    <button type="button" class="p-3 bg-blue-400 text-white rounded-full hover:bg-blue-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">
                    ← Retour à l'accueil
                </a>
            </div>
        </div>
        
    </div>
    
</body>
</html>
```

### 4.3 Page de connexion Admin (auth/login-admin.blade.php)

Première fois : setup avec mot de passe
Fois suivantes : email + mot de passe

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - STAGILOG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-blue-900 to-blue-600">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG" class="h-20 mx-auto mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Espace Administration</h3>
            <p class="text-gray-600 mt-2">Connexion sécurisée</p>
        </div>
        
        @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
            {{ $errors->first() }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('login.admin.submit') }}">
            @csrf
            
            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="admin@tfg-sarl.com" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="••••••••" required>
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-600">Se souvenir de moi</span>
                </label>
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Se connecter
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">
                ← Retour à l'accueil
            </a>
        </div>
        
    </div>
    
</body>
</html>
```

### 4.4 Page premier setup admin (auth/first-time-setup.blade.php)

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier paramétrage - STAGILOG</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen flex items-center justify-center bg-gradient-to-br from-green-900 to-green-600">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        
        <div class="text-center mb-8">
            <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Première connexion</h3>
            <p class="text-gray-600 mt-2">Veuillez définir un nouveau mot de passe sécurisé</p>
        </div>
        
        @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form method="POST" action="{{ route('first-time-setup.submit') }}">
            @csrf
            
            <div class="mb-6">
                <label for="old_password" class="block text-gray-700 font-medium mb-2">
                    Mot de passe actuel
                </label>
                <input type="password" name="old_password" id="old_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       placeholder="Admin@2026" required>
            </div>
            
            <div class="mb-6">
                <label for="new_password" class="block text-gray-700 font-medium mb-2">
                    Nouveau mot de passe
                </label>
                <input type="password" name="new_password" id="new_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       placeholder="Minimum 8 caractères" required>
                <p class="text-xs text-gray-500 mt-1">
                    Au moins 8 caractères, une majuscule, un chiffre et un caractère spécial
                </p>
            </div>
            
            <div class="mb-6">
                <label for="new_password_confirmation" class="block text-gray-700 font-medium mb-2">
                    Confirmer le mot de passe
                </label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                       placeholder="Retapez le mot de passe" required>
            </div>
            
            <button type="submit" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                Définir le nouveau mot de passe
            </button>
        </form>
        
    </div>
    
</body>
</html>
```

---

## 🔐 PHASE 5 : CONTRÔLEURS & MIDDLEWARE

### 5.1 Middleware CheckRole

```bash
php artisan make:middleware CheckRole
```

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}
```

### 5.2 Middleware CheckFirstLogin

```bash
php artisan make:middleware CheckFirstLogin
```

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFirstLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->first_login) {
            return redirect()->route('first-time-setup');
        }

        return $next($request);
    }
}
```

Enregistrer dans `bootstrap/app.php` :

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
        'first.login' => \App\Http\Middleware\CheckFirstLogin::class,
    ]);
})
```

### 5.3 Contrôleur LoginController

```bash
php artisan make:controller Auth/LoginController
```

```php
<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Afficher formulaire login école
    public function showEcoleLoginForm()
    {
        return view('auth.login-ecole');
    }
    
    // Afficher formulaire login admin
    public function showAdminLoginForm()
    {
        return view('auth.login-admin');
    }
    
    // Traiter login école
    public function loginEcole(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt(array_merge($credentials, ['role' => 'ecole']))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.ecole'));
        }
        
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }
    
    // Traiter login admin
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            
            // Vérifier si première connexion
            if (Auth::user()->first_login) {
                return redirect()->route('first-time-setup');
            }
            
            return redirect()->intended(route('dashboard.admin'));
        }
        
        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }
    
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }
}
```

### 5.4 Contrôleur FirstTimeSetupController

```bash
php artisan make:controller Auth/FirstTimeSetupController
```

```php
<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FirstTimeSetupController extends Controller
{
    public function show()
    {
        if (!Auth::check() || !Auth::user()->first_login) {
            return redirect()->route('dashboard.admin');
        }
        
        return view('auth.first-time-setup');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
            ],
        ], [
            'new_password.regex' => 'Le mot de passe doit contenir au moins une majuscule, un chiffre et un caractère spécial.',
        ]);
        
        $user = Auth::user();
        
        // Vérifier l'ancien mot de passe
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mot de passe actuel incorrect.']);
        }
        
        // Mettre à jour
        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->first_login_at = now();
        $user->save();
        
        return redirect()->route('dashboard.admin')->with('success', 'Mot de passe défini avec succès !');
    }
}
```

---

## 🛣️ PHASE 6 : ROUTES

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\FirstTimeSetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DossierController;
use App\Http\Controllers\EcolesController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\EtudiantsController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Auth Routes
Route::prefix('auth')->group(function () {
    // Login École
    Route::get('/ecole/login', [LoginController::class, 'showEcoleLoginForm'])->name('login.ecole');
    Route::post('/ecole/login', [LoginController::class, 'loginEcole'])->name('login.ecole.submit');
    
    // Login Admin
    Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
    Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('login.admin.submit');
    
    // First Time Setup (Admin seulement)
    Route::get('/first-time-setup', [FirstTimeSetupController::class, 'show'])
         ->middleware('auth')
         ->name('first-time-setup');
    Route::post('/first-time-setup', [FirstTimeSetupController::class, 'update'])
         ->middleware('auth')
         ->name('first-time-setup.submit');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Routes protégées
Route::middleware(['auth', 'first.login'])->group(function () {
    
    // Dashboard École
    Route::middleware('role:ecole')->group(function () {
        Route::get('/dashboard/ecole', [DashboardController::class, 'ecole'])->name('dashboard.ecole');
        
        // Dossiers
        Route::prefix('dossiers')->group(function () {
            Route::get('/create', [DossierController::class, 'create'])->name('dossiers.create');
            Route::post('/store', [DossierController::class, 'store'])->name('dossiers.store');
            Route::get('/{id}/edit', [DossierController::class, 'edit'])->name('dossiers.edit');
            Route::put('/{id}', [DossierController::class, 'update'])->name('dossiers.update');
            Route::post('/{id}/submit', [DossierController::class, 'submit'])->name('dossiers.submit');
            Route::get('/{id}', [DossierController::class, 'show'])->name('dossiers.show');
        });
        
        // Rapports (consultation)
        Route::get('/rapports', [RapportController::class, 'indexEcole'])->name('rapports.ecole');
    });
    
    // Dashboard Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        
        // Gestion des écoles
        Route::resource('ecoles', EcolesController::class);
        Route::post('/ecoles/{id}/regenerer-mdp', [EcolesController::class, 'regenererMotDePasse'])
             ->name('ecoles.regenerer-mdp');
        
        // Gestion des filières
        Route::resource('filieres', FiliereController::class);
        Route::post('/filieres/{id}/toggle-actif', [FiliereController::class, 'toggleActif'])
             ->name('filieres.toggle');
        
        // Gestion des dossiers (tous)
        Route::get('/admin/dossiers', [DossierController::class, 'index'])->name('admin.dossiers');
        Route::post('/admin/dossiers/{id}/valider', [DossierController::class, 'valider'])
             ->name('admin.dossiers.valider');
        Route::post('/admin/dossiers/{id}/refuser', [DossierController::class, 'refuser'])
             ->name('admin.dossiers.refuser');
        
        // Gestion des rapports
        Route::prefix('admin/rapports')->group(function () {
            Route::get('/', [RapportController::class, 'indexAdmin'])->name('admin.rapports');
            Route::get('/ecole/{id}', [RapportController::class, 'parEcole'])->name('admin.rapports.ecole');
            Route::get('/dossier/{id}', [RapportController::class, 'parDossier'])->name('admin.rapports.dossier');
            Route::post('/depot', [RapportController::class, 'depot'])->name('admin.rapports.depot');
        });
    });
    
});
```

---

## 📧 PHASE 7 : MAILS

### 7.1 Mail de bienvenue école

```bash
php artisan make:mail WelcomeEcoleMail
```

```php
<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEcoleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ecole;
    public $username;
    public $password;

    public function __construct($ecole, $username, $password)
    {
        $this->ecole = $ecole;
        $this->username = $username;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Bienvenue sur STAGILOG - TFG SARL')
                    ->view('emails.welcome-ecole');
    }
}
```

**Vue email** : `resources/views/emails/welcome-ecole.blade.php`

```blade
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header img { max-width: 150px; }
        .credentials { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .button { display: inline-block; background: #2563eb; color: white; padding: 12px 24px; 
                  text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; margin-top: 30px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-tfg.png') }}" alt="TFG SARL">
            <h2>Bienvenue sur STAGILOG</h2>
        </div>
        
        <p>Bonjour <strong>{{ $ecole->nom_ecole }}</strong>,</p>
        
        <p>Votre compte école a été créé avec succès sur la plateforme STAGILOG.</p>
        
        <div class="credentials">
            <h3>Vos identifiants de connexion :</h3>
            <p><strong>Nom d'utilisateur :</strong> {{ $username }}</p>
            <p><strong>Mot de passe :</strong> {{ $password }}</p>
        </div>
        
        <p>Vous pouvez vous connecter dès maintenant :</p>
        
        <center>
            <a href="{{ route('login.ecole') }}" class="button">Se connecter</a>
        </center>
        
        <p style="margin-top: 20px; font-size: 14px; color: #6b7280;">
            Pour des raisons de sécurité, nous vous recommandons de changer votre mot de passe 
            lors de votre première connexion.
        </p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Technology Forever Group SARL</p>
            <p>Tous droits réservés</p>
        </div>
    </div>
</body>
</html>
```

---

## ✅ PHASE 8 : COMMANDES ARTISAN

```bash
# Créer les migrations
php artisan make:migration create_cycles_table
php artisan make:migration create_filieres_table
php artisan make:migration add_nouveau_workflow_fields_to_dossiers_table
php artisan make:migration add_nouveau_workflow_fields_to_etudiants_table
php artisan make:migration create_emails_historique_table
php artisan make:migration add_first_login_to_users_table
php artisan make:migration add_contact_fields_to_ecoles_table

# Créer les modèles
php artisan make:model Cycle
php artisan make:model Filiere
php artisan make:model EmailHistorique

# Créer les seeders
php artisan make:seeder CycleSeeder
php artisan make:seeder FiliereSeeder
php artisan make:seeder AdminSeeder

# Créer les contrôleurs
php artisan make:controller DashboardController
php artisan make:controller FiliereController --resource
php artisan make:controller RapportController

# Créer les middlewares
php artisan make:middleware CheckRole
php artisan make:middleware CheckFirstLogin

# Créer les mails
php artisan make:mail WelcomeEcoleMail

# Exécuter les migrations et seeders
php artisan migrate
php artisan db:seed
```

---

## 🧪 PHASE 9 : TESTS & VALIDATION

### Checklist de tests :

**Installation & Configuration** :
- [ ] Migrations exécutées sans erreur
- [ ] Seeders exécutés (cycles, filières, admin)
- [ ] Config email fonctionnelle (.env : MAIL_*)
- [ ] Images copiées dans `public/images/`

**Authentification** :
- [ ] Page d'accueil s'affiche avec logo TFG
- [ ] Lien "Espace École" redirige vers login école
- [ ] Lien "Espace Admin" redirige vers login admin
- [ ] Login école fonctionne
- [ ] Login admin redirige vers first-time-setup
- [ ] First-time-setup valide le nouveau mot de passe
- [ ] Logout fonctionne

**Dashboard École** :
- [ ] Stats affichées correctement
- [ ] Liens de navigation fonctionnent

**Création de dossier** :
- [ ] Formulaire charge les cycles et filières depuis BDD
- [ ] Upload de CV, contrat, autres docs fonctionne
- [ ] Mode brouillon enregistre sans soumettre
- [ ] Page de validation s'affiche
- [ ] Bouton "Soumettre" change le statut

**Dashboard Admin** :
- [ ] Stats globales affichées
- [ ] Liste des dossiers en attente
- [ ] Validation/Refus fonctionne

**Gestion des écoles** :
- [ ] Créer une école + envoi email
- [ ] Modifier infos école
- [ ] Régénérer mot de passe + envoi email
- [ ] Email reçu dans boîte mail

**Gestion des filières** :
- [ ] Liste des filières
- [ ] Ajouter une filière
- [ ] Modifier une filière
- [ ] Activer/Désactiver une filière

**Gestion des rapports** :
- [ ] Admin peut rechercher étudiant par nom/prénom
- [ ] Dépôt de rapport (rapport_etudiant, pv_stage, autre)
- [ ] École peut consulter les rapports déposés

---

## 📦 FICHIERS RÉCAPITULATIFS

**Nouveaux fichiers créés** (34) :
```
Migrations (7)
Modèles (3)
Seeders (3)
Contrôleurs (6)
Middleware (2)
Vues (10)
Mails (1)
Routes (1)
Images (2)
```

**Fichiers modifiés** (5) :
```
app/Models/Dossier.php
app/Models/Etudiant.php
app/Models/Ecole.php
bootstrap/app.php
config/mail.php
```

---

## 🎯 PROCHAINES ÉTAPES

1. **Copier les images** dans `public/images/`
2. **Exécuter les commandes Artisan**
3. **Configurer l'envoi d'emails** (.env)
4. **Tester le workflow complet**
5. **Ajuster le design** si nécessaire
6. **Déployer en production**

---

**FIN DU PLAN D'IMPLÉMENTATION LARAVEL**

Ce plan respecte intégralement les nouvelles exigences + le design des images fournies.
Tout est prêt pour une implémentation Laravel moderne et sécurisée !
