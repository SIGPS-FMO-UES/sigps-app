<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['nombre_usuario', 'nombre_completo', 'email', 'password', 'activo'])]
#[Hidden(['password', 'remember_token'])]
class Usuario extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    protected string $guard_name = 'web';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Proyectos creados por este usuario (administrador/coordinador).
     */
    public function proyectosCreados(): HasMany
    {
        return $this->hasMany(Proyecto::class, 'creado_por', 'id_usuario');
    }

    /**
     * Memorias registradas por este usuario.
     */
    public function memoriasRegistradas(): HasMany
    {
        return $this->hasMany(Memoria::class, 'registrada_por', 'id_usuario');
    }

    /**
     * Registro de logs de acciones realizadas por el usuario.
     */
    public function logsAcciones(): HasMany
    {
        return $this->hasMany(LogAccion::class, 'usuario', 'id_usuario');
    }
}
