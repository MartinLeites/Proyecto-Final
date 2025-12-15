<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CredencialTienda extends Model
{
    protected $table = 'credencialesTiendas';

    protected $primaryKey = 'IDCredencial';

    public $timestamps = false;
    protected $fillable = [
        'IDTienda',
        'Tipo',
        'Valor'
    ];
    
    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'IDTienda', 'IDTienda');
    }
}
