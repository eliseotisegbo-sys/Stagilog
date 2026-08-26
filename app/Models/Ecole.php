<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    protected $table = 'ecoles';
    protected $primaryKey = 'id_ecole';

    protected $fillable = [
        'nom_ecole',
        'sigle',
        'adresse_ecole',
        'logo',
        'num_ecole',
        'mail',
        'email',
        'telephone',
    ];

    /**
     * Relation: Une école peut avoir plusieurs users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_ecole', 'id_ecole');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_ecole', 'id_ecole');
    }

    /**
     * Relation: Une école peut avoir plusieurs dossiers
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_ecole', 'id_ecole');
    }
    
    /**
     * Relation: Une école peut avoir plusieurs emails historiques
     */
    public function emailsHistorique()
    {
        return $this->hasMany(EmailHistorique::class, 'id_ecole', 'id_ecole');
    }
}
