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

        $datedebut = $etudiant->dossier->datedebut;
        $stageCommence = $datedebut ? now()->startOfDay()->gte($datedebut->startOfDay()) : true;

        return view('admin.rapports.depot', compact('etudiant', 'datedebut', 'stageCommence'));
    }

    /**
     * Déposer un ou plusieurs nouveaux documents liés à l'étudiant (Support multi-fichiers, brouillons & publication)
     */
    public function storeDepot(Request $request, $id)
    {
        $etudiant = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->findOrFail($id);

        $datedebut = $etudiant->dossier->datedebut;
        $stageCommence = $datedebut ? now()->startOfDay()->gte($datedebut->startOfDay()) : true;
        $isDraft = ($request->input('action') === 'brouillon') || !$stageCommence;

        // Préparer la liste des documents à traiter (support format unique ou multiple)
        $items = [];
        if ($request->has('documents') && is_array($request->documents)) {
            $items = $request->documents;
        } elseif ($request->filled('nom_document') && $request->hasFile('fichier')) {
            $items[] = [
                'nom_document' => $request->nom_document,
                'fichier' => $request->file('fichier'),
            ];
        }

        if (empty($items)) {
            return back()->with('error', 'Veuillez renseigner au moins un titre et joindre le fichier correspondant.');
        }

        $savedCount = 0;
        $uploadedNames = [];

        foreach ($items as $item) {
            if (empty($item['nom_document']) || empty($item['fichier'])) {
                continue;
            }

            $file = $item['fichier'];
            if (!$file->isValid()) {
                continue;
            }

            $fileName = 'doc_' . $etudiant->id_etudiant . '_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $fileSize = round($file->getSize() / 1024, 1) . ' Ko';
            if ($file->getSize() > 1048576) {
                $fileSize = round($file->getSize() / 1048576, 2) . ' Mo';
            }

            $file->move(public_path('uploads/rapports'), $fileName);

            $statutDoc = $isDraft ? 'brouillon' : 'publie';

            $etudiant->documents()->create([
                'nom_document' => trim($item['nom_document']),
                'fichier' => $fileName,
                'taille_fichier' => $fileSize,
                'statut' => $statutDoc,
            ]);

            if ($statutDoc === 'publie' && !$etudiant->rapport) {
                $etudiant->rapport = $fileName;
                $etudiant->save();
            }

            $uploadedNames[] = trim($item['nom_document']);
            $savedCount++;
        }

        if ($savedCount === 0) {
            return back()->with('error', 'Aucun document valide n\'a pu être enregistré. Veuillez vérifier les fichiers.');
        }

        // Si publié, publier aussi tous les brouillons existants de cet étudiant
        if (!$isDraft) {
            EtudiantDocument::where('id_etudiant', $etudiant->id_etudiant)
                ->where('statut', 'brouillon')
                ->update(['statut' => 'publie']);

            // Notification à l'école
            $docListStr = implode(', ', $uploadedNames);
            \App\Models\AppNotification::notifier(
                'ecole',
                'Nouveaux Documents Déposés',
                "Documents ({$docListStr}) déposés par TFG SARL pour {$etudiant->nom_etudiant} {$etudiant->prenom_etudiant}.",
                route('ecole.rapports.index'),
                'rapport_depose',
                $etudiant->dossier->id_ecole
            );

            return redirect()->route('admin.rapports.depot', $etudiant->id_etudiant)
                ->with('success', "{$savedCount} document(s) publié(s) avec succès pour {$etudiant->nom_etudiant} {$etudiant->prenom_etudiant} ! L'école a été notifiée.");
        }

        $msg = "{$savedCount} document(s) enregistré(s) en Brouillon. ";
        if (!$stageCommence && $datedebut) {
            $msg .= "Ils seront transmissibles dès le début du stage le " . $datedebut->format('d/m/Y') . ".";
        } else {
            $msg .= "Vous pourrez les publier définitivement à tout moment.";
        }

        return redirect()->route('admin.rapports.depot', $etudiant->id_etudiant)->with('info', $msg);
    }

    /**
     * Publier définitivement tous les documents en brouillon d'un étudiant
     */
    public function publishDrafts($id)
    {
        $etudiant = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->findOrFail($id);

        $datedebut = $etudiant->dossier->datedebut;
        $stageCommence = $datedebut ? now()->startOfDay()->gte($datedebut->startOfDay()) : true;

        if (!$stageCommence) {
            return back()->with('error', "Le stage de cet étudiant débute le " . $datedebut->format('d/m/Y') . ". Vous ne pouvez pas publier les documents avant cette date.");
        }

        $count = EtudiantDocument::where('id_etudiant', $etudiant->id_etudiant)
            ->where('statut', 'brouillon')
            ->update(['statut' => 'publie']);

        if ($count > 0) {
            \App\Models\AppNotification::notifier(
                'ecole',
                'Nouveaux Documents Publiés',
                "Des documents officiels de stage ont été publiés par TFG SARL pour {$etudiant->nom_etudiant} {$etudiant->prenom_etudiant}.",
                route('ecole.rapports.index'),
                'rapport_depose',
                $etudiant->dossier->id_ecole
            );
        }

        return back()->with('success', "Tous les documents en brouillon ({$count}) ont été publiés et transmis avec succès.");
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

    /**
     * Liste dédiée des étudiants en stage actuellement avec suivi de progression
     */
    public function stagiairesActifs(Request $request)
    {
        $search = $request->query('search');

        $etudiants = Etudiant::whereHas('dossier', function($q) {
                $q->where('statut', 'valide');
            })
            ->with(['dossier.ecole'])
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nom_etudiant', 'like', "%{$search}%")
                        ->orWhere('prenom_etudiant', 'like', "%{$search}%")
                        ->orWhere('email_etu', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('admin.rapports.stagiaires-actifs', compact('etudiants', 'search'));
    }
}
