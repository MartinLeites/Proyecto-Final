<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrador extends Model
{
    protected $table = 'administradors';
    protected $primaryKey = 'IDPersona';
    public $timestamps = false;

    protected $fillable = ['IDPersona'];


   public function persona()
    {
        return $this->belongsTo(Persona::class, 'IDPersona', 'IDPersona');
    }
}