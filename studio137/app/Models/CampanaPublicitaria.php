<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampanaPublicitaria extends Model
{
    use HasFactory;

    protected $table = 'campanas_publicitarias';

    protected $fillable = [
        'servicio_id',
        'responsable_marketing_id',
        'nombre_campana',
        'objetivo',
        'plataforma',
        'fecha_inicio',
        'fecha_fin',
        'presupuesto',
        'estado',
        'url_archivo'
    ];

    protected $casts = [
        'presupuesto' => 'decimal:2'
    ];

    public const PLATAFORMAS = ['Facebook', 'Instagram', 'Google', 'TikTok', 'Otro'];
    public const ESTADOS = ['planificada', 'en curso', 'finalizada', 'cancelada'];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function responsableMarketing(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'responsable_marketing_id');
    }
}