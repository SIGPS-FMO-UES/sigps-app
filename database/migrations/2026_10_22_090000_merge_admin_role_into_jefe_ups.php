<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Move accounts and permissions from the retired admin role to jefe_ups.
     */
    public function up(): void
    {
        $tables = config('permission.table_names');
        $roles = DB::table($tables['roles'])
            ->where('name', 'admin')
            ->where('guard_name', 'web')
            ->get();

        if ($roles->isEmpty()) {
            return;
        }

        $jefatura = DB::table($tables['roles'])
            ->where('name', 'jefe_ups')
            ->where('guard_name', 'web')
            ->first();

        if (! $jefatura) {
            $jefaturaId = DB::table($tables['roles'])->insertGetId([
                'name' => 'jefe_ups',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $jefaturaId = $jefatura->id;
        }

        foreach ($roles as $admin) {
            $permissionIds = DB::table($tables['role_has_permissions'])
                ->where('role_id', $admin->id)
                ->pluck('permission_id');

            foreach ($permissionIds as $permissionId) {
                DB::table($tables['role_has_permissions'])->insertOrIgnore([
                    'role_id' => $jefaturaId,
                    'permission_id' => $permissionId,
                ]);
            }

            $assignments = DB::table($tables['model_has_roles'])
                ->where('role_id', $admin->id)
                ->get();

            foreach ($assignments as $assignment) {
                DB::table($tables['model_has_roles'])->insertOrIgnore([
                    'role_id' => $jefaturaId,
                    'model_type' => $assignment->model_type,
                    'model_id' => $assignment->model_id,
                ]);
            }

            DB::table($tables['roles'])->where('id', $admin->id)->delete();
        }

        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * The admin role is retired permanently; its data cannot be restored safely.
     */
    public function down(): void
    {
        // Intentionally irreversible: accounts and permissions now belong to jefe_ups.
    }
};
