<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\EtudiantDocument;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RapportController extends Controller
{
    /**
     * Liste des rapports et étudiants (STRICTEMENT RÉSERVÉ AUX DOSSIERS VALIDÉS)
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        // UNIQUEMENT les étudiants dont le dossier est VALIDÉ par TFG SARL
        $etudiants = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->with(['dossier.ecole', 'documents'])
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nom_etudiant', 'like', "%{$search}%")
                        ->orWhere('prenom_etudiant', 'like', "%{$search}%")
                        ->orWhere('email_etu', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15);

        $totalEtudiantsValides = Etudiant::whereHas('dossier', function($q) {
            $q->where('statut', 'valide');
        })->count();

        $totalDocumentsDeposes = EtudiantDocument::whereHas('etudiant.dossier', function($q) {
            $q->where('statut', 'valide');
        })->count();

        return view('admin.rapports.index', compact('etudiants', 'search', 'totalEtudiantsValides', 'totalDocumentsDeposes'));
    }

    /**
     * Formulaire / Gestion des documents déposés pour un étudiant
     */
    public function depot($id)
    {
        $etudiant = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->with(['dossier.ecole', 'documents'])
            ->findOrFail($id);

        return view('admin.rapports.depot', compact('etudiant'));
    }

    /**
     * Déposer un nouveau document lié au rapport de l'étudiant avec nom personnalisé
     */
    public function storeDepot(Request $request, $id)
    {
        $etudiant = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->findOrFail($id);

        $request->validate([
            'nom_document' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:20480',
        ], [
            'nom_document.required' => 'Veuillez préciser le nom du document (ex: Rapport de Stage, Procès-Verbal, Attestation...).',
            'fichier.required' => 'Veuillez joindre le fichier à déposer.',
            'fichier.mimes' => 'Le fichier doit être au format PDF, Word, Excel ou ZIP.',
        ]);

        $file = $request->file('fichier');
        $fileName = 'doc_' . $etudiant->id_etudiant . '_' . time() . '_' . rand(10, 99) . '.' . $file->getClientOriginalExtension();
        $fileSize = round($file->getSize() / 1024, 1) . ' Ko';
        if ($file->getSize() > 1048576) {
            $fileSize = round($file->getSize() / 1048576, 2) . ' Mo';
        }

        $file->move(public_path('uploads/rapports'), $fileName);

        // Enregistrer le document
        $etudiant->documents()->create([
            'nom_document' => $request->nom_document,
            'fichier' => $fileName,
            'taille_fichier' => $fileSize,
        ]);

        // Mise à jour de compatibilité sur etudiant->rapport
        if (!$etudiant->rapport) {
            $etudiant->rapport = $fileName;
            $etudiant->save();
        }

        // Notification à l'école
        \App\Models\AppNotification::notifier(
            'ecole',
            'Nouveau Document Déposé',
            "Un document '{$request->nom_document}' a été déposé par TFG SARL pour {$etudiant->nom_etudiant} {$etudiant->prenom_etudiant}.",
            route('ecole.rapports.index'),
            'rapport_depose',
            $etudiant->dossier->id_ecole
        );

        return redirect()->route('admin.rapports.depot', $etudiant->id_etudiant)
            ->with('success', "Le document '{$request->nom_document}' a été déposé avec succès pour {$etudiant->nom_etudiant} {$etudiant->prenom_etudiant} !");
    }

    /**
     * Supprimer un document spécifique
     */
    public function destroyDocument($id)
    {
        $document = EtudiantDocument::findOrFail($id);
        $etudiantId = $document->id_etudiant;

        $filePath = public_path('uploads/rapports/' . $document->fichier);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $document->delete();

        return redirect()->route('admin.rapports.depot', $etudiantId)->with('success', "Le document a été supprimé.");
    }
}
