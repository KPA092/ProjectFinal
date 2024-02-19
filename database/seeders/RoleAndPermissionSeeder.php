<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleAndPermissionSeeder extends Seeder
{
	public function run()
	{
		$permissionsProduct = [
			'products.index',
			'products.show',
			'products.store',
			'products.update',
			'products.destroy',
			'product.add-to-cart',
			'categories.index',
			'categories.get-all',
			'categories.get-all-dt',
			'categories.create',
			'categories.store',
			'categories.edit',
			'categories.update',
			'categories.destroy',
			'cart_item.index',
			'cart_item.update',
			'cart_item.store',
			'cart_item.destroy',

		];

		$permissionsAdmin = array_merge([
			'users.index',
			'users.create',
			'users.store',
			'users.edit',
			'users.update',
			'users.destroy',
		], $permissionsProduct);

		// Roles
		$admin = Role::create(['name' => 'admin']);
		$product = Role::create(['name' => 'product']);
		Role::create(['name' => 'user']);

		foreach ($permissionsAdmin as $permission) {
			$permission = Permission::create(['name' => $permission]);
			$admin->givePermissionTo($permission);
		}
		foreach ($permissionsProduct as $permission) {
			$permission = Permission::where(['name' => $permission])->first();
			$product->givePermissionTo($permission);
		}
	}
}
