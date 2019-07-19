<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
         // creacion de permisos
        
        /*permisos para editar,eliminar,crear y ver usuario*/
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'watch user']);
        
        /*permisos para editar,eliminar,crear y ver minimercado*/
        Permission::create(['name' => 'edit minimarket']);
        Permission::create(['name' => 'delete minimarket']);
        Permission::create(['name' => 'create minimarket']);
        Permission::create(['name' => 'watch minimarket']);
        
        /*permisos para editar,eliminar,crear y ver venta*/
        Permission::create(['name' => 'edit sale']);
        Permission::create(['name' => 'delete sale']);
        Permission::create(['name' => 'create sale']);
        Permission::create(['name' => 'watch sale']);
        
        /*permisos para editar,eliminar,crear y ver categoria*/
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'watch category']);

        /*permisos para editar,eliminar,crear y ver producto*/
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'delete product']);
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'watch product']);
        
        /*permisos para agregar categoria,producto,usuario,venta a un minimercado*/
        Permission::create(['name' => 'add category']);
        Permission::create(['name' => 'add product']);
        Permission::create(['name' => 'add user']);
        Permission::create(['name' => 'add sale']);

        // create roles and assign created permissions

        
        $role = Role::create(['name' => 'super_admin']);
        $role->givePermissionTo(Permission::all());

        // or may be done by chaining
        $role = Role::create(['name' => 'minimarket_admin'])
            ->givePermissionTo(['add sale','add category','add product','watch minimarket','watch category', 'watch product', 'watch sale','edit user','create user','delete user','watch user','edit minimarket','watch minimarket','edit sale','create sale','watch sale','edit product','delete product','create product','watch product']);

        // this can be done as separate statements
        $role = Role::create(['name' => 'minimarket_user']);
        $role->givePermissionTo(['add sale','add category','add product','watch minimarket','watch category', 'watch product', 'watch sale']);

    }
}
