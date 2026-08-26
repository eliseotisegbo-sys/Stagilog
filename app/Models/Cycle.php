<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cycle extends Model
{
    protected $table = 'cycles';
    protected $primaryKey = 'id_cycle';
    
    protected $fillable = ['nom_cycle'];
    
    /**
     * Relation: Un cycle peut avoir plusieurs dossiers
     */
    public function dossiers()
    {
        return $this->hasMany(Dossier::class, 'id_cycle', 'id_cycle');
    }
}
