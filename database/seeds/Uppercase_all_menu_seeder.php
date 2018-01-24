<?php

use Illuminate\Database\Seeder;
use App\Restaurant_menu;
class Uppercase_all_menu_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = Restaurant_menu::all();
        foreach ($menus as $menu) {
          $menu->category = strtoupper($menu->category);
          $menu->subcategory = strtoupper($menu->subcategory);
          $menu->name = strtoupper($menu->name);
          $menu->save();
          echo "\n".$menu->category.' - '.$menu->name;
        }
    }
}
