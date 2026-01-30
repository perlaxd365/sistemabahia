<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'genero',
        'dni',
        'fecha_nacimiento',
        'nombre_cargo',
        'especialidad_cargo',
        'colegiatura_cargo',
        'privilegio_cargo',
        'direccion',
        'telefono',
        'foto_url',
        'firma_url',
        'password',
        'estado_user',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function adminlte_profile_url()
    {
        // Aquí coloca la ruta a tu perfil, por ejemplo:
        return 'perfil'; // o 'user/profile'
    }

    public function adminlte_image()
    {
        // Imagen del usuario
        return auth()->user()->foto_url;
    }

    public function adminlte_desc()
    {
        return  auth()->user()->nombre_cargo;
    }

    public function cajaTurnos()
    {
        return $this->hasMany(CajaTurno::class, 'user_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'user_id');
    }
}
