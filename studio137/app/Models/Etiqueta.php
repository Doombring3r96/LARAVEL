<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etiqueta extends Model
{
    use HasFactory;

    protected $table = 'etiquetas';

    protected $fillable = [
        'nombre',
        'color',
        'descripcion'
    ];

    public function tareas(): BelongsToMany
    {
        return $this->belongsToMany(Tarea::class, 'tarea_etiqueta');
    }
}