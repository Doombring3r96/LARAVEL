<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar de manera masiva.
     */
    protected $fillable = [
        'email',
        'password',
        'tipo',
        'estado',
    ];

    /**
     * Los atributos que deben estar ocultos al serializar.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ soporta esto para auto-hashear
    ];

    /**
     * Valores posibles para 'tipo'
     */
    public const TIPOS = ['administrador', 'trabajador', 'cliente'];

    /**
     * Valores posibles para 'estado'
     */
    public const ESTADOS = ['activo', 'inactivo'];
}
