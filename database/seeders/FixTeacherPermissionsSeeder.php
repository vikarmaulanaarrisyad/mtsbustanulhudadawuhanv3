<?php
  
namespace Database\Seeders;
  
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
  
class FixTeacherPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or ensure group exists
        $group = PermissionGroup::firstOrCreate(['name' => 'Absensi Guru']);
  
        // 2. Define required permissions
        $permissions = [
            'teacher-attendance.view',
            'teacher-attendance.create',
            'teacher-face-registration.view',
            'dashboard.guru'
        ];
  
        foreach ($permissions as $permission) {
            $p = Permission::where('name', $permission)->where('guard_name', 'web')->first();
            if (!$p) {
                $p = Permission::create([
                    'name' => $permission,
                    'permission_group_id' => $group->id,
                    'guard_name' => 'web'
                ]);
            } else {
                $p->update(['permission_group_id' => $group->id]);
            }
        }
  
        // 3. Assign to Guru role
        $guruRole = Role::where('name', 'Guru')->first();
        if ($guruRole) {
            $guruRole->givePermissionTo($permissions);
            $this->command->info('Permissions assigned to Guru role.');
        } else {
            $this->command->error('Guru role not found.');
        }

        // 4. Also assign to Admin and Super Admin for convenience
        foreach (['Admin', 'Super Admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }
}
