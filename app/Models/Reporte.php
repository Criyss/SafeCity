<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'titulo',
        'descripcion',
        'latitud',
        'longitud',
        'foto_base64',
        'estado',
        'departamento',
        'gravedad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function estados()
    {
        return $this->hasMany(EstadoReporte::class);
    }
}
