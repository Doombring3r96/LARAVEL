<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tareas';

    protected $fillable = [
        'actividad_id',
        'trabajador_id',
        'titulo',
        'descripcion',
        'prioridad',
        'fecha_inicio',
        'fecha_fin_estimada',
        'fecha_fin_real',
        'estado'
    ];

    public const PRIORIDADES = ['alta', 'media', 'baja'];
    public const ESTADOS = ['pendiente', 'en curso', 'en revisión', 'completada', 'retrasada'];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class);
    }

    public function piezasGraficas(): HasMany
    {
        return $this->hasMany(PiezaGrafica::class);
    }

    public function etiquetas(): BelongsToMany
    {
        return $this->belongsToMany(Etiqueta::class, 'tarea_etiqueta');
    }
}