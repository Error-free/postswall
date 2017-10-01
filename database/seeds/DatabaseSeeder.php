<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$catQuantity = 2;
		$brandQuantity = 2;
		$propQuantity = 1;
		$propValQuantity = 2;

		for($i = 1; $i <= $brandQuantity; $i++) {
			DB::table('brands')->insert([
				'id' => $i,
				'name' => 'Брэнд № ' . $i,
			]);
		}

		for($i = 1; $i <= $catQuantity; $i++) {
			DB::table('categorys')->insert([
				'id' => $i,
				'name' => 'Категория № ' . $i,
			]);
		}

		for($i = 1; $i <= $propQuantity; $i++) {
			DB::table('propertys')->insert([
				'id' => $i,
				'name' => 'Свойство № ' . $i,
			]);
		}

		$i = 0;
		for($bi = 1; $bi <= $brandQuantity; $bi++) {
			for($ci = 1; $ci <= $catQuantity; $ci++) {
				$i++;
				DB::table('products')->insert([
					'id' => $i,
					'name' => 'Продукт № ' . $i,
					'category_id' => $ci,
					'brand_id' => $bi,
				]);
			}
		}
		$productQuantity = $i;

		$i = 0;
		for($pi = 1; $pi <= $propQuantity; $pi++) {
			for($pvi = 1; $pvi <= $propValQuantity; $pvi++) {
				$i++;
				DB::table('property_values')->insert([
					'id' => $i,
					'name' => 'Значение № ' . $i,
					'property_id' => $pi,
				]);
			}
		}

		for($i = 1; $i <= $productQuantity; $i++) {
			DB::table('products_property_values')->insert([
				'id' => $i,
				'product_id' => $i,
				'property_value_id' => mt_rand(1, $propValQuantity)
			]);
		}
	}
}