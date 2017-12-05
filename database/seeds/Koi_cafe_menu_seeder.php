<?php

use Illuminate\Database\Seeder;
use App\Restaurant_menu;
class Koi_cafe_menu_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      $viewdeck_menu = Restaurant_menu::where('restaurant_id',1)->get();
      foreach ($viewdeck_menu as $menu_data) {
        $restaurant_menu = new Restaurant_menu;
        $restaurant_menu->category = $menu_data->category;
        $restaurant_menu->name = $menu_data->name;
        $restaurant_menu->subcategory = $menu_data->subcategory;
        $restaurant_menu->is_prepared = $menu_data->is_prepared;
        $restaurant_menu->price = $menu_data->price;
        $restaurant_menu->restaurant_id = 2;
        $restaurant_menu->save();
        echo "\n".$restaurant_menu->category.' - '.$restaurant_menu->name;
      }
    }
}
