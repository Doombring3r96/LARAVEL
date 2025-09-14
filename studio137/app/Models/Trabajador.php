<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'usuario_id',
        'nombre_completo',
        'telefono',
        'puesto',
        'sueldo'
    ];

    protected $casts = [
        'sueldo' => 'decimal:2'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    public function evaluacionesDesempeno(): HasMany
    {
        return $this->hasMany(EvaluacionDesempeno::class);
    }

    public function calendariosPublicacion(): HasMany
    {
        return $this->hasMany(CalendarioPublicacion::class, 'responsable_marketing_id');
    }

    public function campanasPublicitarias(): HasMany
    {
        return $this->hasMany(CampanaPublicitaria::class, 'responsable_marketing_id');
    }

    public function artesCalendario(): HasMany
    {
        return $this->hasMany(ArteCalendario::class, 'disenador_id');
    }
}