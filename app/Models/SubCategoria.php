<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_categorias';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',
        'nombre',
        'tipo',
        'estado',
        'deleted_at'
    ];

    public function Categoria(){
        return $this->belongsTo('App\Models\Categoria', 'categoria_id');
    }
}
