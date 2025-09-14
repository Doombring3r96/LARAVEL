<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionDesempeno extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_desempeno';

    protected $fillable = [
        'trabajador_id',
        'mes',
        'anio',
        'total_tareas',
        'tareas_a_tiempo',
        'tareas_retrasadas',
        'porcentaje_cumplimiento',
        'calificacion',
        'observaciones'
    ];

    protected $casts = [
        'porcentaje_cumplimiento' => 'decimal:2'
    ];

    public const CALIFICACIONES = ['excelente', 'bueno', 'regular', 'malo'];

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class);
    }
}