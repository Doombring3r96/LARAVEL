<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'usuario_id',
        'nombre_empresa',
        'persona_contacto',
        'telefono_contacto',
        'deuda'
    ];

    protected $casts = [
        'deuda' => 'decimal:2'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class);
    }
}