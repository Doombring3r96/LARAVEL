<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformeMarketing extends Model
{
    use HasFactory;

    protected $table = 'informes_marketing';

    protected $fillable = [
        'servicio_id',
        'titulo',
        'descripcion',
        'url_archivo',
        'tipo',
        'visible_para_cliente',
        'creado_por'
    ];

    public const TIPOS = ['campaña', 'calendario', 'otro'];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}