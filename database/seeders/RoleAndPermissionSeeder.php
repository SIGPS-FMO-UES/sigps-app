<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Matriz Canónica de Roles y Permisos Granulares del Sistema UPS (SYS-01).
     * Delimitación de arquitectura: Exclusivo Backoffice UPS (ADR-06).
     * Referencia: .agents/workflows/endpoints.md y ADR-05.
     */
    public function run(): void
    {
        // Limpiar caché de permisos en memoria y almacenamiento
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'web';

        $rolesPermisos = [
            'admin' => [
                'usuarios.gestionar',
                'carreras.gestionar',
                'areas.gestionar',
                'plantillas.gestionar',
                'auditoria.consultar',
            ],
            'jefe_ups' => [
                'expedientes.auditar',
                'expedientes.aprobar',
                'expedientes.observar',
                'plazos.consultar',
                'prorrogas.resolver',
                'certificaciones.emitir',
                'estadisticas.exportar',
            ],
            'auxiliar_ups' => [
                'expedientes.auditar',
                'expedientes.observar',
                'estudiantes.buscar',
                'proyectos.modificar',
                'certificaciones.registrar_entrega',
                'estadisticas.consultar',
            ],
        ];

        // 1. Crear todos los permisos únicos
        $todosPermisos = collect($rolesPermisos)->flatten()->unique();
        foreach ($todosPermisos as $nombrePermiso) {
            Permission::firstOrCreate([
                'name' => $nombrePermiso,
                'guard_name' => $guard,
            ]);
        }

        // 2. Crear roles y sincronizar sus permisos correspondientes
        foreach ($rolesPermisos as $nombreRol => $permisos) {
            $rol = Role::firstOrCreate([
                'name' => $nombreRol,
                'guard_name' => $guard,
            ]);

            $rol->syncPermissions($permisos);
        }
    }
}
