<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'mensaje',
        'leido',
        'origen_tipo',
        'origen_id'
    ];

    public const ORIGEN_TIPOS = ['actividad', 'tarea', 'calendario', 'campaña', 'pago'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}