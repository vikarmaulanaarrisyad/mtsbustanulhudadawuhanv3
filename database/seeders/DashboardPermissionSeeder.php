<?php
 
namespace Database\Seeders;
 
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
 
class DashboardPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $group = PermissionGroup::firstOrCreate(['name' => 'Dashboard']);
 
        $permissions = [
            'dashboard.admin',
            'dashboard.guru',
            'dashboard.siswa',
            'dashboard.ppdb',
        ];
 
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'permission_group_id' => $group->id,
                'guard_name' => 'web'
            ]);
        }
 
        // Assign permissions to default roles for convenience
        $adminRole = Role::where('name', 'Admin')->first();
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $guruRole = Role::where('name', 'Guru')->first();
        $siswaRole = Role::where('name', 'Siswa')->first();
        $ppdbRole = Role::where('name', 'ppdb')->first();
 
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo($permissions);
        }
 
        if ($adminRole) {
            $adminRole->givePermissionTo(['dashboard.admin', 'dashboard.guru', 'dashboard.siswa', 'dashboard.ppdb']);
        }
 
        if ($guruRole) {
            $guruRole->givePermissionTo('dashboard.guru');
        }
 
        if ($siswaRole) {
            $siswaRole->givePermissionTo('dashboard.siswa');
        }
 
        if ($ppdbRole) {
            $ppdbRole->givePermissionTo('dashboard.ppdb');
        }
    }
}
