<?php

use Illuminate\Database\Seeder;
use App\Restaurant_menu;
class Front_office_menu_seeder extends Seeder
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
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'CAPA 6',
          'price' =>   4500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'CAPA 7',
          'price' =>   4500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'SUPERIOR VILLA',
          'name' => 'PANDAKAKI',
          'price' =>   8500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'SUPERIOR VILLA',
          'name' => 'DAMA DE NOCHE',
          'price' =>   8500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'SUPERIOR VILLA',
          'name' => 'CICADA',
          'price' =>  8500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'EXECUTIVE VILLA',
          'name' => 'MUSSAENDA',
          'price' =>  10000.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'EXECUTIVE VILLA',
          'name' => 'MARIPOSA',
          'price' =>  10000.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'FAMILY SUITE',
          'name' => 'PALMERA',
          'price' =>  10800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'HONEYMOON SUITE',
          'name' => 'HELICONIA',
          'price' =>   8500.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'FOOD',
          'subcategory' => 'BUFFET',
          'name' => 'DAY TOUR LUNCH BUFFET',
          'price' =>  650.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'FOOD',
          'subcategory' => 'BUFFET',
          'name' => 'DAY TOUR LUNCH BUFFET SENIOR CITIZEN',
          'price' =>  464.29,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'FOOD',
          'subcategory' => 'BUFFET',
          'name' => 'DAY TOUR LUNCH BUFFET 4-11 YO',
          'price' =>  325.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 1',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 2',
          'price' =>   5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 3',
          'price' =>   5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 4',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 5',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 6',
          'price' =>   5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'MEDENILLA 7',
          'price' =>   5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 1',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 2',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 3',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 4',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 5',
          'price' =>   5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 6',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'ROOMS',
          'subcategory' => 'DELUXE ROOMS',
          'name' => 'PAKPAK 7',
          'price' =>  5800.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKDAY',
          'price' =>  250.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKDAY SENIOR CITIZEN',
          'price' =>  200.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKDAY 4-11 YO',
          'price' => 125.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKEND',
          'price' =>  300.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKEND SENIOR CITIZEN',
          'price' =>  240.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'PARKS',
          'subcategory' => 'ENTRANCE',
          'name' => 'GENERAL ADMISSION WEEKEND 4-11 YO',
          'price' =>  150.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'CHOCOLATE MAKING 200G',
          'price' =>  450.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'CHOCOLATE MAKING 100G',
          'price' =>  250.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'CHOCOLATE & WINE PAIRING ',
          'price' =>  395.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 153G WIRO',
          'price' =>  180.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 252G WIRO',
          'price' =>  315.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 500G WIRO',
          'price' =>  435.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 1000G WIRO',
          'price' =>  715.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 2000G WIRO',
          'price' =>  1375.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 750G BLOCK',
          'price' => 530.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS PREMIUM UNSWEETENED CHOCOLATE 1600G BLOCK',
          'price' =>  1070.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 25G SQUARE',
          'price' => 70.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 100G BAR',
          'price' => 180.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 500G WIRO',
          'price' =>  365.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 1000G WIRO',
          'price' => 625.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 2000G WIRO',
          'price' =>  1165.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 750G BLOCK',
          'price' =>  465.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '65% DARK CHOCOLATE 1600G BLOCK',
          'price' =>  905.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '72% DARK CHOCOLATE 25G SQUARE',
          'price' =>  75.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '72% DARK CHOCOLATE 100G BAR',
          'price' =>  200.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '72% DARK CHOCOLATE 500G WIRO',
          'price' =>  390.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '72% DARK CHOCOLATE 1000G',
          'price' =>  645.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '85% DARK CHOCOLATE 25G SQUARE',
          'price' => 80.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '85% DARK CHOCOLATE 100G BAR',
          'price' => 250.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '85% DARK CHOCOLATE 500G WIRO',
          'price' =>  415.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => '85% DARK CHOCOLATE 1000G WIRO',
          'price' => 670.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'THE DARK COLLECTION',
          'price' =>  650.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'SUNDRY',
          'subcategory' => 'CHOCOLATE',
          'name' => 'MALAGOS ROASTED NIBS 250G',
          'price' =>  250.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ SNACKS',
          'price' => 630.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ SNACKS SENIOR CITIZEN',
          'price' =>  504.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ SNACKS 4-11 YO',
          'price' =>  315.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ LUNCH',
          'price' => 980.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ LUNCH SENIOR CITIZEN',
          'price' =>   784.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'TOUR',
          'name' => 'TREE TO BAR TOUR W/ LUNCH 4-11 YO',
          'price' => 490.00,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'PACKAGE',
          'name' => 'PACKAGE - MEALS',
          'price' =>  0,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'PACKAGE',
          'name' => 'PACKAGE - ACCOMMODATION',
          'price' =>  0,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'PACKAGE',
          'name' => 'PACKAGE - MEALS & ACCOMMODATION',
          'price' =>  0,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'PACKAGE',
          'name' => 'PACKAGE - ENTRANCE',
          'price' =>  0,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
        [
          'category' => 'OTHERS',
          'subcategory' => 'PACKAGE',
          'name' => 'PACKAGE - ACTIVITES',
          'price' =>  0,
          'restaurant_id' => 5,
          'is_prepared' => 1
        ],
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
