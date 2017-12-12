<?php

use Illuminate\Database\Seeder;
use App\Restaurant_menu;
class Parks_office_menu extends Seeder
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
          'subcategory' => 'SNACKS',
          'name' => 'PIATOS BIG',
          'price' =>  35.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'PIATOS SMALL',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CHIPPY BIG',
          'price' =>  35.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CHIPPY SMALL',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'NOVA BIG',
          'price' =>  35.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'NOVA SMALL',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CHEESE RING',
          'price' =>  35.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'SKYFLAKES',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CREAM CONE',
          'price' =>  30.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CREAM BAR',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CREAM CUP',
          'price' =>  30.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CREAM SUNDAE',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'MINI CUP',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'PINIPIG',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'PREMIUM CONE',
          'price' =>  35.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'CREAM COOLER',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'GOLD BAR',
          'price' =>  60.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'HOTDOG',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'POPCORN',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'BINGO',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'SNACKS',
          'name' => 'OREO',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'CANDIES',
          'name' => 'DURIAN CANDY',
          'price' =>  20.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'CANDIES',
          'name' => 'MAXX',
          'price' =>  1.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'CANDIES',
          'name' => 'LOLLIPOP',
          'price' =>  1.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Food',
          'subcategory' => 'CANDIES',
          'name' => 'FRUITY LOLLY',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'COKE LIGHT CAN',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'Coke Can',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'RTO CAN',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'SPRITE CAN',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'real leaf',
          'price' =>  90.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'Gatorade',
          'price' =>  100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'SODA',
          'name' => 'C2',
          'price' =>  55.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
        'category' => 'Beverages',
          'subcategory' => 'JUICE CANNED',
          'name' => 'DOLE 4 SEASON',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'JUICE TETRA',
          'name' => 'MINUTE MAID',
          'price' =>  15.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'JUICE TETRA',
          'name' => 'CHOICE JUICE',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Beverages',
          'subcategory' => 'BOTTLED WATER',
          'name' => 'NATURE`S SPRING 500ml',
          'price' =>  40.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'PROCESSED',
          'name' => 'STRAWBERRY JAM',
          'price' =>  250.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'T-SHIRT-ADULTS XS',
          'price' =>  280.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'T-SHIRT-ADULTS-Small',
          'price' =>  350.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'T-SHIRT KIDS-SMALL',
          'price' =>  250.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'MALONG',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'BAG-MGR LOGO PRINTED',
          'price' =>  250.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'REF MAGNET',
          'price' =>  65.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'KEY CHAIN',
          'price' =>  45.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'WALLET BIG',
          'price' =>  120.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'WALLET SMALL',
          'price' =>  60.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'LADIES WALLET',
          'price' =>  120.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'SWIMSUIT ADULT',
          'price' =>  350.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'SWIMSUIT KIDS',
          'price' =>  85.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'CYCLING SHORT-ADULTS',
          'price' =>  160.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'CYCLING SHORT-KIDS',
          'price' =>  85.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'FLOATER BIG',
          'price' =>  160.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'FLOATER SMALL',
          'price' =>  120.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'SLIPPER',
          'price' =>  100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'GOOGLES',
          'price' =>  150.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'WOOD BALLPEN',
          'price' =>  25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'BOARD SHORT',
          'price' =>  280.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'RUSH GUARD JACKET',
          'price' =>  450.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'RUSH GUARD WITH SHORT',
          'price' =>  450.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'MERCHANDISE',
          'name' => 'UMBRELLA',
          'price' =>  150.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'FEEDS',
          'name' => 'KOI FEEDS',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'FEEDS',
          'name' => 'DAWA',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'MODESS',
          'price' =>  10.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'TOOTHBRUSH',
          'price' => 25.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'OFF LOTION',
          'price' => 18.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'TOOTH PASTE',
          'price' => 11.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'SHAMPOO',
          'price' => 8.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Sundry',
          'subcategory' => 'TOILETRIES',
          'name' => 'VCO SMALL',
          'price' => 90.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'TRAMPOLINE 15 MIN',
          'price' => 50.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'INFLATABLE Climb 15 MIN',
          'price' => 50.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'DART1 HR',
          'price' => 80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'BIKING 1 HR',
          'price' => 80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'BILLARD 1 HR',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'BADMINTON 1 HR',
          'price' => 80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'BASKETBALL 1 HR',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'TABLE TENNIS 1 HR',
          'price' =>  80.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'KIDS AVENTURE PAX',
          'price' => 100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'HORSEBACK PAX',
          'price' => 100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'CALESA PAX',
          'price' => 100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'SKY WALKER PAX',
          'price' =>  250.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'SWIMMING-ADULT PAX',
          'price' => 100.00,
          'restaurant_id' => 4,
          'is_prepared' => 1
        ],
        [
          'category' => 'Parks',
          'subcategory' => 'ACTIVITIES',
          'name' => 'SWIMMING-KIDS PAX',
          'price' => 80.00,
          'restaurant_id' => 4,
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
