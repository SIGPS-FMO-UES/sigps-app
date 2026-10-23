<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Keep Spatie assignments on the same model class used by Laravel auth.
     */
    public function up(): void
    {
        $tables = config('permission.table_names');

        foreach ([
            $tables['model_has_roles'] => ['role_id'],
            $tables['model_has_permissions'] => ['permission_id'],
        ] as $table => $keys) {
            $legacyAssignments = DB::table($table)
                ->where('model_type', App\Models\Usuario::class)
                ->get();

            foreach ($legacyAssignments as $assignment) {
                $row = [
                    'model_type' => User::class,
                    'model_id' => $assignment->model_id,
                ];

                foreach ($keys as $key) {
                    $row[$key] = $assignment->{$key};
                }

                DB::table($table)->insertOrIgnore($row);
                DB::table($table)
                    ->where('model_type', App\Models\Usuario::class)
                    ->where('model_id', $assignment->model_id)
                    ->where($keys[0], $assignment->{$keys[0]})
                    ->delete();
            }
        }

        app(Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Intentionally irreversible: assignments are normalized to the auth model.
    }
};
