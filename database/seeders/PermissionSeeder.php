<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Backpack\PermissionManager\app\Models\Permission;
use Backpack\PermissionManager\app\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database Permission seed.

     * Permissions are fixed in code and are seeded here.
     * use 'php artisan db:seed --class=PermissionSeeder --force' in production
     *
     * @return void
     */
    public function run()
    {
        // create permission for each combination of table.level
        // \DB::table('permissions')->delete();
        collect([ // tables
            'settings',
            'best_employee_in_team',
            'best_manager_in_company',
            'employee_of_the_month'
            // 'holidays',
            // 'roles',
            // 'courses',
            // 'departments',
            // 'employes',
            // 'enrollments',
            // 'events',
            // 'execuses',
            // 'it_tickets',
            // 'jobs',
            // 'medical_incurence',
            // 'social_incurence',
            // 'vacations',
            // 'work_from_home',
            // 'policy_documents',
        ])
            ->crossJoin([ // levels
                'see',
                'edit',
                // 'ss',
            ])
            ->each(
                fn (array $item) => Permission::firstOrCreate([
                    'name' => implode('.', $item),
                ])
                    ->save()
            )
            //
        ;
        User::first()
            ->givePermissionTo(['users.edit']);
    }
}
