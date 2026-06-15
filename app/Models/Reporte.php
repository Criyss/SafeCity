<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $fillable = [
        'user_id',
        'categoria_id',
        'departamento',
        'titulo',
        'descripcion',
        'latitud',
        'longitud',
        'foto_base64',
        'gravedad',
        'estado',
        'fecha_incidente',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function estadosReporte()
    {
        return $this->hasMany(EstadoReporte::class);
    }
}
