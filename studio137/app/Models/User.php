<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'tipo',
        'subtipo',
        'estado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Valores posibles para 'tipo'
    public const TIPOS = ['administrador', 'trabajador', 'cliente'];
    
    // Valores posibles para 'subtipo' de administrador
    public const SUBTIPOS_ADMIN = ['CEO', 'director_cuentas', 'director_creativo'];
    
    // Valores posibles para 'subtipo' de trabajador
    public const SUBTIPOS_TRABAJADOR = ['disenador_grafico', 'marketing'];
    
    // Valores posibles para 'estado'
    public const ESTADOS = ['activo', 'inactivo'];

    // Relación con cliente
    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'usuario_id');
    }

    // Relación con trabajador
    public function trabajador()
    {
        return $this->hasOne(Trabajador::class, 'usuario_id');
    }

    // Verificar si es CEO
    public function isCeo()
    {
        return $this->tipo === 'administrador' && $this->subtipo === 'CEO';
    }

    // Verificar si es Director de Cuentas
    public function isDirectorCuentas()
    {
        return $this->tipo === 'administrador' && $this->subtipo === 'director_cuentas';
    }

    // Verificar si es Director Creativo
    public function isDirectorCreativo()
    {
        return $this->tipo === 'administrador' && $this->subtipo === 'director_creativo';
    }

    // Verificar si es Diseñador Gráfico
    public function isDisenadorGrafico()
    {
        return $this->tipo === 'trabajador' && $this->subtipo === 'disenador_grafico';
    }

    // Verificar si es Marketing
    public function isMarketing()
    {
        return $this->tipo === 'trabajador' && $this->subtipo === 'marketing';
    }

    // Verificar si es Cliente
    public function isCliente()
    {
        return $this->tipo === 'cliente';
    }
}