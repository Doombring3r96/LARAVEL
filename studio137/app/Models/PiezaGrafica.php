<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiezaGrafica extends Model
{
    use HasFactory;

    protected $table = 'piezas_graficas';

    protected $fillable = [
        'tarea_id',
        'tipo',
        'titulo',
        'copy',
        'descripcion',
        'estado',
        'url_archivo',
        'fecha_entrega_estimada',
        'fecha_entrega_real'
    ];

    public const TIPOS = ['arte publicitario', 'logotipo', 'papelería', 'otro'];
    public const ESTADOS = ['en diseño', 'en revisión', 'aprobado', 'rechazado'];

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }
}