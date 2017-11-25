<?php

use Illuminate\Database\Seeder;
use App\Restaurant_menu;
class Chocobar_menu_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_list = array();
        $menu_list = 
        [
          [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'ENSAYMADA',
            'price' => 25,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'DOUGHNUT',
            'price' => '25',
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'DANISH BREAD',
            'price' => 20,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'SUMAN',
            'price' => 45,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'DURIAN ICE CREAM',
            'price' => 50,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'VANILLA ICE CREAM',
            'price' => 45,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'MANGO ICE CREAM',
            'price' => 45,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'UBE ICE CREAM',
            'price' => 45,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'RED VELVET ICE CREAM',
            'price' => 45,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverage',
            'subcategory' => 'CHOCOLATE DRINKS',
            'name' => 'HOUSEBLEND WITH SUMAN',
            'price' => 145,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverage',
            'subcategory' => 'CHOCOLATE DRINKS',
            'name' => 'HOUSEBLEND ONLY',
            'price' => 100,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverage',
            'subcategory' => 'CHOCOLATE DRINKS',
            'name' => 'CHOCOLATE FREEZE',
            'price' => 170,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'CHOCO SAMPLER',
            'price' => 225,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'CHOCOLATE & WINE PAIRING',
            'price' => 395,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 153G WIRO',
            'price' => 180,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 252G WIRO',
            'price' => 315,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 500G WIRO',
            'price' => 435,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 1000G WIRO',
            'price' => 715,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 2000G WIRO',
            'price' => 1375,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 750G BLOCK',
            'price' => 530,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 1600G BLOCK',
            'price' => 1070,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 25G SQUARE',
            'price' => 70,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 100G BAR',
            'price' => 180,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 500G WIRO',
            'price' => 365,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 1000G WIRO',
            'price' => 625,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 2000G WIRO',
            'price' => 1165,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 750G BLOCK',
            'price' => 465,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 1600G BLOCK',
            'price' => 905,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 25G SQUARE',
            'price' => 75,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 100G BAR',
            'price' => 200,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 500G WIRO',
            'price' => 390,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 1000G',
            'price' => 645,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 25G SQUARE',
            'price' => 80,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 100G BAR',
            'price' => 250,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 500G WIRO',
            'price' => 415,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 1000G WIRO',
            'price' => 670,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'THE DARK COLLECTION',
            'price' => 650,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'MALAGOS ROASTED NIBS 250G',
            'price' => 315,
            'restaurant_id' => 3,
            'is_prepared' => 1
          ]
        ];
        foreach ($menu_list as $menu_data) {
          $menu = new Restaurant_menu;
          $menu->category = $menu_data['category'];
          $menu->subcategory = $menu_data['subcategory'];
          $menu->name = $menu_data['name'];
          $menu->price = $menu_data['price'];
          $menu->restaurant_id = $menu_data['restaurant_id'];
          $menu->is_prepared = $menu_data['is_prepared'];
          $menu->save();
          echo "\n".$menu_data['category'].' - '.$menu_data['name'];
        }
    }
}
