<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_ecole',
        'role',
        'first_login',
        'first_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'first_login' => 'boolean',
            'first_login_at' => 'datetime',
        ];
    }

    /**
     * Relation: Un user appartient à une école
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'id_ecole', 'id_ecole');
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Vérifier si l'utilisateur est une école
     */
    public function isEcole()
    {
        return $this->role === 'ecole';
    }
}
