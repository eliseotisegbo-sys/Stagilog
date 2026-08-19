<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class dossier extends Model
{
    protected $table = 'dossiers';
    protected $primaryKey = 'id_dossier';

    protected $fillable = [
        'annee_academique',
        'filiere',
        'lettredemande',
        'datedebut',
        'datefin',
        'id_ecole',
        'statut',
    ];

    protected $casts = [
        'datedebut' => 'date',
        'datefin' => 'date',
    ];

    /**
     * Relation: Un dossier appartient à une école
     */
    public function ecole()
    {
        return $this->belongsTo(ecoles::class, 'id_ecole', 'id_ecole');
    }

    /**
     * Relation: Un dossier peut avoir plusieurs étudiants
     */
    public function etudiants()
    {
        return $this->hasMany(etudiants::class, 'id_dossier', 'id_dossier');
    }
}
