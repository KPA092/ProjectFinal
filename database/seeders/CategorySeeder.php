<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

	public function run()
	{
		Category::create(['name' => 'Gorras']);
		Category::create(['name' => 'Reloj']);
		Category::create(['name' => 'Ollas']);
	}
}
