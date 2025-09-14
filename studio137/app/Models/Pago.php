<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'usuario_id',
        'tipo_pago',
        'servicio_id',
        'monto',
        'fecha_pago',
        'url_comprobante',
        'descripcion'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'date'
    ];

    public const TIPOS_PAGO = ['cliente', 'trabajador'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class);
    }
}