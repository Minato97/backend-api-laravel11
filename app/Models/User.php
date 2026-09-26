<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'roles_id',
        'estatus_id',
        'password',
        'foto'
    ];

    public function roles(){
        return $this->belongsTo(Rol::class,'roles_id');
    }
    public function estatus(){
        return $this->belongsTo(Estatus::class,'estatus_id');
    }

    public function isAdmin(): bool
    {
        return $this->roles?->rol === Rol::ADMINISTRADOR;
    }

    /**
     * Crea un usuario desde un registro público: siempre con rol "Usuario" y estatus "Activo",
     * sin permitir que el cliente elija su propio rol.
     */
    public static function registrar(array $datos): self
    {
        return static::create([
            ...$datos,
            'roles_id' => Rol::where('rol', Rol::USUARIO)->value('id'),
            'estatus_id' => Estatus::where('estatus', Estatus::ACTIVO)->value('id'),
        ]);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
