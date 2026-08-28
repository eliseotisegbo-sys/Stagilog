<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtudiantDocument extends Model
{
    use HasFactory;

    protected $table = 'etudiant_documents';
    protected $primaryKey = 'id_document';

    protected $fillable = [
        'id_etudiant',
        'nom_document',
        'fichier',
        'taille_fichier',
        'statut',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant', 'id_etudiant');
    }
}
