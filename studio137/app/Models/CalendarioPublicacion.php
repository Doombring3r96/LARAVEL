<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarioPublicacion extends Model
{
    use HasFactory;

    protected $table = 'calendarios_publicacion';

    protected $fillable = [
        'servicio_id',
        'responsable_marketing_id',
        'mes_publicacion',
        'anio_publicacion',
        'url_documento',
        'estado',
        'fecha_creacion',
        'fecha_aprobacion',
        'fecha_publicacion'
    ];

    public const ESTADOS = ['borrador', 'revisión', 'aprobado', 'publicado'];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function responsableMarketing(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'responsable_marketing_id');
    }

    public function artes(): HasMany
    {
        return $this->hasMany(ArteCalendario::class, 'calendario_id');
    }
}