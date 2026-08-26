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
    
    /**
     * Relation: Un email appartient à une école
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'id_ecole', 'id_ecole');
    }
}
