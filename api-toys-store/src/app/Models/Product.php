<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * $fillable = "Campos que acepto que me llenen desde fuera"
     * SEGURIDAD: Evita que alguien envíe campos maliciosos
     */
    public $incrementing = false;

    // Make sure the primary key is set to the correct column name
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'nombre',           
        'precio_actual',   
        'categoria',        

    ];

    /**
     * $casts = "Cómo convertir los datos automáticamente"
     * CONVENIENCIA: Laravel convierte por ti
     */
    protected $casts = [
        'disponible' => 'boolean',      // "1" → true, "0" → false
        'precio_actual' => 'decimal:2', // "99.99" → 99.99 (decimal)
        'valoracion' => 'decimal:1',    // "4.5" → 4.5 (decimal)
    ];


}