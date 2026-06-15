<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoReporte extends Model
{
    protected $table = 'estados_reporte';

    protected $fillable = [
        'reporte_id',
        'user_id',
        'estado',
        'comentario',
    ];

    public function reporte()
    {
        return $this->belongsTo(Reporte::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
