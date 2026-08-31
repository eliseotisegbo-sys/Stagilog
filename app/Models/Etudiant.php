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
        'photo_profil',
        'cv',
        'datedebut_stage',
        'datefin_stage',
        'contrat',
        'autres_documents',
        'rapport',
        'pv_stage',
        'type_rapport',
        'id_dossier',
    ];
    
    protected $casts = [
        'date_naissance'  => 'date',
        'datedebut_stage' => 'date',
        'datefin_stage'   => 'date',
        'autres_documents' => 'array',
    ];

    /**
     * Relation: Un étudiant appartient à un dossier
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }

    /**
     * Relation: Un étudiant a plusieurs documents déposés (rapports, PV, attestations...)
     */
    public function documents()
    {
        return $this->hasMany(EtudiantDocument::class, 'id_etudiant', 'id_etudiant');
    }
    
    /**
     * Vérifier si l'étudiant a un rapport
     */
    public function hasRapport()
    {
        return !is_null($this->rapport);
    }
    
    /**
     * Vérifier si l'étudiant a un PV de stage
     */
    public function hasPvStage()
    {
        return !is_null($this->pv_stage);
    }
    
    /**
     * Obtenir le nom complet de l'étudiant
     */
    public function getNomCompletAttribute()
    {
        return $this->nom_etudiant . ' ' . $this->prenom_etudiant;
    }
}
