<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArteCalendario extends Model
{
    use HasFactory;

    protected $table = 'artes_calendario';

    protected $fillable = [
        'calendario_id',
        'disenador_id',
        'titulo',
        'copy',
        'descripcion',
        'fecha_publicacion_programada',
        'url_arte',
        'estado'
    ];

    public const ESTADOS = ['en diseño', 'en revisión', 'aprobado', 'publicado'];

    public function calendario(): BelongsTo
    {
        return $this->belongsTo(CalendarioPublicacion::class, 'calendario_id');
    }

    public function disenador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'disenador_id');
    }
}