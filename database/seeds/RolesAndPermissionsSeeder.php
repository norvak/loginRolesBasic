<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['guard_name' => 'api', 'name' => 'create']);
        Permission::create(['guard_name' => 'api', 'name' => 'update']);
        Permission::create(['guard_name' => 'api', 'name' => 'delete']);
     //   Permission::create(['name' => 'unpublish articles']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['guard_name' => 'api', 'name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        Role::create(['guard_name' => 'api', 'name' => 'employee']);
        Role::create(['guard_name' => 'api', 'name' => 'customer']);

        // or may be done by chaining
        // $role = Role::create(['name' => 'moderator'])
        //     ->givePermissionTo(['publish articles', 'unpublish articles']);

        // Assign rol user super-admin
          $user = User::find(1);
          $user->assignRole('super-admin');

        // Assign rol user super-admin
        //  $user = User::find(2);
        //  $user->assignRole('employee');

        // Assign rol user super-admin
        // $user = User::find(3);
        // $user->assignRole('customer');

    }
}