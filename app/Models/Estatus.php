<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;

    public const ACTIVO = 'Activo';
    public const INACTIVO = 'Inactivo';

    protected $table = 'estatus';

    protected $fillable = [
        'estatus'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'estatus_id');
    }
}
