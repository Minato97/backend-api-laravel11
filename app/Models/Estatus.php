<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;

    protected $table = 'estatus';

    protected $fillable = [
        'estatus'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

    public function reportes()
    {
        return $this->hasMany(Report::class, 'estatus_id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'estatus_id');
    }
}
