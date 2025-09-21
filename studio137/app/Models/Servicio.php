<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'cliente_id',
        'tipo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin_estimada',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin_estimada' => 'datetime',
    ];

    public const TIPOS = ['identidad corporativa', 'marketing digital', 'campañas publicitarias'];
    public const ESTADOS = ['en espera', 'en proceso', 'en revisión', 'finalizado', 'cancelado'];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function calendariosPublicacion(): HasMany
    {
        return $this->hasMany(CalendarioPublicacion::class);
    }

    public function campanasPublicitarias(): HasMany
    {
        return $this->hasMany(CampanaPublicitaria::class);
    }

    public function informesMarketing(): HasMany
    {
        return $this->hasMany(InformeMarketing::class);
    }
}