<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presentacione extends Model
{
    use HasFactory;

    protected $fillable = [
        'caracteristica_id',
    ];

    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }

    public function caracteristicas()
    {
        return $this->belongsTo(Caracteristica::class, 'caracteristica_id');
    }
}
