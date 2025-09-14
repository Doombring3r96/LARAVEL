<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'servicio_id',
        'nombre',
        'descripcion',
        'orden',
        'fecha_inicio_estimada',
        'fecha_fin_estimada',
        'fecha_inicio_real',
        'fecha_fin_real',
        'estado'
    ];

    public const ESTADOS = ['pendiente', 'en progreso', 'en revisión', 'finalizada', 'cancelada'];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }
}