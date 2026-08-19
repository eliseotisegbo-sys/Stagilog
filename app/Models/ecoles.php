<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ecoles extends Model
{
    protected $table = 'ecoles';
    protected $primaryKey = 'id_ecole';

    protected $fillable = [
        'nom_ecole',
        'adresse_ecole',
        'num_ecole',
        'mail',
    ];

    /**
     * Relation: Une école peut avoir plusieurs users
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_ecole', 'id_ecole');
    }

    /**
     * Relation: Une école peut avoir plusieurs dossiers
     */
    public function dossiers()
    {
        return $this->hasMany(dossier::class, 'id_ecole', 'id_ecole');
    }
}
