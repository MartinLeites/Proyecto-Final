<?php

/*
--INUTIL DE MOMENTO--
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoInterno extends Model
{
    protected $table = 'productoInternos';

    protected $primaryKey = 'IDProductoI';

    protected $fillable = ['Nombre', 'Marca', 'Modelo'];

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'pertenece', 'IDProductoI', 'IDCategoria');
    }

    public function productosTienda()
    {
        return $this->hasMany(ProductoTienda::class, 'IDProductoI', 'IDProductoI');
    }
}
*/
