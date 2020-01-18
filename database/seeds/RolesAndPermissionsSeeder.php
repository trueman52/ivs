<?php

use App\Enums\UserStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{

    /**
     * All Permissions
     *
     * @return void
     */
    public function createPermissions()
    {

        $collection = collect([
            'users',
            'roles',
            'permissions',
            'add_ons',
            'add_on_groups',
            'discounts',
            'spaces',
            'periods',
            'units',
            'event_homes',
            'customer_details',
            'tags',
            'coupons',
            'bookings',
            'booth_assignments',
        ]);

        $collection->each(function ($item) {
            // create permissions for each collection item
            Permission::updateOrCreate(['name' => 'view any ' . Str::plural($item)], ['group' => $item, 'name' => 'view any ' . Str::plural($item)]);
            Permission::updateOrCreate(['name' => 'view ' . $item], ['group' => $item, 'name' => 'view ' . $item]);
            Permission::updateOrCreate(['name' => 'create ' . $item], ['group' => $item, 'name' => 'create ' . $item]);
            Permission::updateOrCreate(['name' => 'update ' . $item], ['group' => $item, 'name' => 'update ' . $item]);
            Permission::updateOrCreate(['name' => 'delete ' . $item], ['group' => $item, 'name' => 'delete ' . $item]);
            Permission::updateOrCreate(['name' => 'restore ' . $item], ['group' => $item, 'name' => 'restore ' . $item]);
            Permission::updateOrCreate(['name' => 'force delete ' . $item], ['group' => $item, 'name' => 'force delete ' . $item]);
        });

        Permission::updateOrCreate(['name' => 'access admin dashboard'], ['group' => 'administrative']);
    }

    /**
     * All Roles
     *
     * @return void
     */
    public function createRoles()
    {
        $roles = [
            ['name' => Role::SUPER_ADMIN],
            //['name' => Role::COORDINATOR],
            ['name' => Role::CUSTOMER],
        ];
        // create roles for each collection item
        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role], $role);
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createPermissions();
        $this->createRoles();

        // Create a Super-Admin Role and assign all Permissions

        $role = Role::where('name', Role::SUPER_ADMIN)->first();
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        // $user = App\User::whereEmail('your@email.com')->first(); // Change this to your email.
        // $user->assignRole('super-admin');

        $admins = [
            [
                'first_name' => 'Admin',
                'last_name'  => 'User',
                'email'      => 'admin@annanovas.com',
                'password'   => bcrypt('111111'),
                'status'     => UserStatus::ACTIVE(),
            ],
            [
                'first_name' => 'Xenon',
                'last_name'  => 'Thong',
                'email'      => 'xenonthong@gmail.com',
                'password'   => bcrypt('pass'),
                'status'     => UserStatus::ACTIVE(),
            ]
        ];

        foreach ($admins as $admin) {
            $user = User::forceCreate($admin);

            $user->assignRole(Role::SUPER_ADMIN);
        }
    }

}
