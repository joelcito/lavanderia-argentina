<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        //se aumentan los siguientes atributos a la tabla users
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'rol_id',
        'sucursal_id',
        'nombres',
        'ap_paterno',
        'ap_materno',
        'cedula',
        'direccion',
        'celular',

        'estado',
        'deleted_at',

        'pago_diario',
        'horas_base',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // public function rol(){
    //     return $this->belongsTo('App\Models\Rol', 'rol_id');
    // }

    // public function sucursal(){
    //     return $this->belongsTo(Sucursal::class, 'sucursal_id');
    // }

    public function isAdmin()
    {
        return $this->rol_id == 1 ? true : false;
    }

    public function isLavador()
    {
        return $this->rol_id == 2 ? true : false;
    }

    public function isCliente()
    {
        return $this->rol_id == 3 ? true : false;
    }

    public function isEncargadoAlmacen()
    {
        return $this->rol_id == 4 ? true : false;
    }

    public function isPlanchador()
    {
        return $this->rol_id == 5 ? true : false;
    }

    public function isFocalizador()
    {
        return $this->rol_id == 6 ? true : false;
    }

    public function isAyudanteLavado()
    {
        return $this->rol_id == 7 ? true : false;
    }

    public function isAuxuliarOficina()
    {
        return $this->rol_id == 8 ? true : false;
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Models\Sucursal', 'sucursal_id');
    }

    public function rol()
    {
        return $this->belongsTo('App\Models\Rol', 'rol_id');
    }
}
