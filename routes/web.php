<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\FirstTimeSetupController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\Admin\EcoleController as AdminEcoleController;
use App\Http\Controllers\Admin\DossierController as AdminDossierController;
use App\Http\Controllers\Admin\RapportController as AdminRapportController;
use App\Http\Controllers\Admin\FiliereController as AdminFiliereController;
use App\Http\Controllers\Ecole\DossierController as EcoleDossierController;
use App\Http\Controllers\Ecole\RapportController as EcoleRapportController;

// ============================================
// PAGE D'ACCUEIL PUBLIQUE (ÉCOLES SEULEMENT)
// ============================================
Route::get('/', function () {
    return view('welcome-standalone');
})->name('welcome');

// ============================================
// AUTHENTIFICATION & RÉCUPÉRATION
// ============================================
Route::prefix('auth')->group(function () {
    
    // Login École
    Route::get('/ecole/login', [LoginController::class, 'showEcoleLoginForm'])->name('login.ecole');
    Route::post('/ecole/login', [LoginController::class, 'loginEcole'])->name('login.ecole.submit');
    
    // Login Admin (URL privée)
    Route::get('/admin/login', [LoginController::class, 'showAdminLoginForm'])->name('login.admin');
    Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('login.admin.submit');
    
    // First Time Setup (Admin seulement)
    Route::get('/first-time-setup', [FirstTimeSetupController::class, 'show'])
         ->middleware('auth')
         ->name('first-time-setup');
    Route::post('/first-time-setup', [FirstTimeSetupController::class, 'update'])
         ->middleware('auth')
         ->name('first-time-setup.submit');
    
    // Réinitialisation de mot de passe
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// ============================================
// ROUTES PROTÉGÉES : DASHBOARDS & NOTIFICATIONS
// ============================================
Route::middleware(['auth', 'first.login'])->group(function () {
    
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'readAndRedirect'])->name('notifications.readAndRedirect');

    // Dashboard École
    Route::middleware('role:ecole')->group(function () {
        Route::get('/dashboard/ecole', [DashboardController::class, 'ecole'])->name('dashboard.ecole');
    });
    
    // Dashboard Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });
    
});

// ============================================
// ROUTES ESPACE ÉCOLE (role:ecole)
// ============================================
Route::middleware(['auth', 'first.login', 'role:ecole'])->prefix('ecole')->name('ecole.')->group(function () {
    // Dossiers
    Route::get('/dossiers', [EcoleDossierController::class, 'index'])->name('dossiers.index');
    Route::get('/dossiers/create', [EcoleDossierController::class, 'create'])->name('dossiers.create');
    Route::post('/dossiers', [EcoleDossierController::class, 'store'])->name('dossiers.store');
    Route::get('/dossiers/{id}', [EcoleDossierController::class, 'show'])->name('dossiers.show');
    Route::get('/dossiers/{id}/edit', [EcoleDossierController::class, 'edit'])->name('dossiers.edit');
    Route::put('/dossiers/{id}', [EcoleDossierController::class, 'update'])->name('dossiers.update');
    Route::delete('/dossiers/{id}', [EcoleDossierController::class, 'destroy'])->name('dossiers.destroy');

    // Rapports
    Route::get('/rapports', [EcoleRapportController::class, 'index'])->name('rapports.index');

    // Paramètres Espace École
    Route::get('/parametres', [ParametreController::class, 'ecoleIndex'])->name('parametres.index');
    Route::post('/parametres', [ParametreController::class, 'ecoleUpdate'])->name('parametres.update');

    // Saisie du nom du responsable connecté (intercepteur connexion)
    Route::post('/set-session-user', [ParametreController::class, 'setSessionUser'])->name('set-session-user');
});

// ============================================
// ROUTES ESPACE ADMINISTRATION (role:admin)
// ============================================
Route::middleware(['auth', 'first.login', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Gestion des Écoles & Comptes
    Route::resource('ecoles', AdminEcoleController::class);
    Route::post('/ecoles/{id}/creer-compte', [AdminEcoleController::class, 'creerCompte'])->name('ecoles.creer-compte');
    Route::post('/ecoles/{id}/update-password', [AdminEcoleController::class, 'updatePassword'])->name('ecoles.update-password');

    // Gestion des Dossiers de Stage
    Route::get('/dossiers', [AdminDossierController::class, 'index'])->name('dossiers.index');
    Route::get('/dossiers/{id}', [AdminDossierController::class, 'show'])->name('dossiers.show');
    Route::post('/dossiers/{id}/valider', [AdminDossierController::class, 'valider'])->name('dossiers.valider');
    Route::post('/dossiers/{id}/refuser', [AdminDossierController::class, 'refuser'])->name('dossiers.refuser');
    Route::delete('/dossiers/{id}', [AdminDossierController::class, 'destroy'])->name('dossiers.destroy');

    // Gestion & Dépôt des Rapports & Documents Multiples
    Route::get('/rapports', [AdminRapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/{id}/depot', [AdminRapportController::class, 'depot'])->name('rapports.depot');
    Route::post('/rapports/{id}/depot', [AdminRapportController::class, 'storeDepot'])->name('rapports.depot.store');
    Route::delete('/rapports/document/{id}', [AdminRapportController::class, 'destroyDocument'])->name('rapports.document.destroy');
    Route::delete('/rapports/{id}', [AdminRapportController::class, 'destroy'])->name('rapports.destroy');

    // Gestion des Filières & Cycles
    Route::get('/filieres', [AdminFiliereController::class, 'index'])->name('filieres.index');
    Route::post('/filieres', [AdminFiliereController::class, 'store'])->name('filieres.store');
    Route::post('/filieres/{id}/toggle', [AdminFiliereController::class, 'toggle'])->name('filieres.toggle');
    Route::delete('/filieres/{id}', [AdminFiliereController::class, 'destroy'])->name('filieres.destroy');

    // Gestion des Cycles (CRUD)
    Route::post('/cycles', [AdminFiliereController::class, 'storeCycle'])->name('cycles.store');
    Route::delete('/cycles/{id}', [AdminFiliereController::class, 'destroyCycle'])->name('cycles.destroy');

    // Paramètres Espace Admin
    Route::get('/parametres', [ParametreController::class, 'adminIndex'])->name('parametres.index');
    Route::post('/parametres', [ParametreController::class, 'adminUpdate'])->name('parametres.update');
});
