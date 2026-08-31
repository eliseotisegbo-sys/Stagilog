<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    protected $table = 'dossiers';
    protected $primaryKey = 'id_dossier';

    protected $fillable = [
        'code_dossier',
        'annee_academique',
        'filiere',
        'sigle',
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
        'motif_refus',
        'valide_par',
        'valide_par_id',
        'refuse_par',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dossier) {
            if (empty($dossier->code_dossier)) {
                $ecole = Ecole::find($dossier->id_ecole);
                $sigle = $ecole && !empty($ecole->sigle) ? strtoupper($ecole->sigle) : 'STAGE';
                $dossier->code_dossier = $sigle . '-' . date('dmYHi');
            }
        });
    }

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

    public function scopeRefuse($query)
    {
        return $query->where('statut', 'refuse');
    }
}
