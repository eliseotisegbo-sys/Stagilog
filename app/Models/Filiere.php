<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    protected $table = 'filieres';
    protected $primaryKey = 'id_filiere';
    
    protected $fillable = [
        'nom_filiere',
        'description',
        'actif'
    ];
    
    protected $casts = [
        'actif' => 'boolean',
    ];
    
    /**
     * Relation: Une filière peut avoir plusieurs dossiers
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_filiere', 'id_filiere');
    }
    
    /**
     * Scope: Filières actives uniquement
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
