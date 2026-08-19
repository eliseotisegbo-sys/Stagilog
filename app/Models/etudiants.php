<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class etudiants extends Model
{
    protected $table = 'etudiants';
    protected $primaryKey = 'id_etudiant';

    protected $fillable = [
        'nom_etudiant',
        'prenom_etudiant',
        'email_etu',
        'cv',
        'rapport',
        'id_dossier',
    ];

    /**
     * Relation: Un étudiant appartient à un dossier
     */
    public function dossier()
    {
        return $this->belongsTo(dossier::class, 'id_dossier', 'id_dossier');
    }
}
