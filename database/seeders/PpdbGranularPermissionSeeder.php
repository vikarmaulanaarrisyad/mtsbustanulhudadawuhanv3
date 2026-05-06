<?php
 
namespace Database\Seeders;
 
use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
 
class PpdbGranularPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $group = PermissionGroup::firstOrCreate(['name' => 'PPDB Advanced']);
 
        $permissions = [
            'ppdb.view',
            'ppdb.create',
            'ppdb.edit',
            'ppdb.delete',
            'ppdb.verify',
        ];
 
        foreach ($permissions as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'permission_group_id' => $group->id,
                'guard_name' => 'web'
            ]);
        }
    }
}
