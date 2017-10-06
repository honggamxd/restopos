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
        // $this->call(UsersTableSeeder::class);
      DB::table('app_config')->insert([
          'categories' => 'Food,Beverages,Sundry,Others',
          'settlements' => 'cash,credit,debit,cheque,guest_ledger,send_bill,free_of_charge',
          'version' => '1.0000',
      ]);

      DB::table('restaurant')->insert(
        [
          ['name' => 'Viewdeck Café'],
          ['name' => 'Koi Café'],
          ['name' => 'Roberto’s Garden Restaurant']
        ]
      );

      DB::table('user')->insert(
        [
          [
            'username' => 'admin',
            'name' => 'admin name',
            'password' => md5('admin'),
            'privilege' => 'admin',
            'restaurant_id' => 0
          ],
          [
            'username' => 'user1',
            'name' => 'user1 name',
            'password' => md5('user1'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 1
          ],
          [
            'username' => 'user2',
            'name' => 'user2 name',
            'password' => md5('user2'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 2
          ],
          [
            'username' => 'user3',
            'name' => 'user3 name',
            'password' => md5('user3'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 3
          ],
          [
            'username' => 'admin1',
            'name' => 'admin1 name',
            'password' => md5('admin1'),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 1
          ],
          [
            'username' => 'admin2',
            'name' => 'admin2 name',
            'password' => md5('admin2'),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 2
          ],
          [
            'username' => 'admin3',
            'name' => 'admin3 name',
            'password' => md5('admin3'),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 3
          ],
        ]
      );

      DB::table('issuance_to')->insert(
        [
          ['name' => 'Viewdeck Café','ref_id'=>1,'ref_table'=>'restaurant'],
          ['name' => 'Koi Café','ref_id'=>2,'ref_table'=>'restaurant'],
          ['name' => 'Restaurant 3','ref_id'=>3,'ref_table'=>'restaurant']
        ]
      );

      DB::table('restaurant_menu')->insert(
        [
     
		  [
            'category' => 'Food',
            'subcategory' => 'APPETIZER',
            'name' => 'CALAMARES',
            'price' => '225',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'APPETIZER',
            'name' => 'FISH FINGER',
            'price' => '230',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'APPETIZER',
            'name' => 'CHICKEN FINGER',
            'price' => '255',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'BACON',
            'price' => '230',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'BANGUS',
            'price' => '190',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'HAM',
            'price' => '170',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'LONGANISA',
            'price' => '190',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'TAPA',
            'price' => '220',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BREAKFAST',
            'name' => 'TOCINO',
            'price' => '190',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'GRILLED PLATTER',
            'name' => 'CHICKEN INASAL',
            'price' => '215',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'GRILLED PLATTER',
            'name' => 'GRILLED MALASUGUE',
            'price' => '250',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'GRILLED PLATTER',
            'name' => 'PORK BARBECUE',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'AVOCADO ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'BUKO PANDAN ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'COOKIES AND CREAM ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'FRUIT SALAD ICE CREAM',
            'price' => '50',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'MANGO ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'STRAWBERRY ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'TABLEYA ICE CREAM',
            'price' => '60',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'UBE ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'ICE CREAM',
            'name' => 'VANILLA ICE CREAM',
            'price' => '45',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'BEEF SALPACIAO',
            'price' => '310',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'BEEF STEAK TAGALOG',
            'price' => '285',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'BICOL EXPRESS',
            'price' => '315',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'CHICKEN PORK ADOBO',
            'price' => '315',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'CRISPY PATA',
            'price' => '560',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'CRISPY PORK BINAGOONGAN',
            'price' => '195',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'CRISPY SISIG',
            'price' => '285',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'CRISPY TILAPIA',
            'price' => '345',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'FRIED CHICKEN',
            'price' => '315',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'GAMBAS AL AJILLO',
            'price' => '285',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'GRILLED TUNA',
            'price' => '200',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'LECHON KAWALI',
            'price' => '295',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'PANCIT KANTON',
            'price' => '150',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'PANCIT KANTON WITH CHICKEN',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'PORK HUMBA',
            'price' => '420',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'PORK SPARE RIBS',
            'price' => '570',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'SEAFOOD KARE KARE',
            'price' => '345',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'SOTANGHON GUISADO',
            'price' => '150',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'SWEET AND SOUR FISH',
            'price' => '330',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'TUNA/CHICKEN SANDWICH',
            'price' => '190',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'MAIN DISH',
            'name' => 'TWICED-COOK CHICKEN ADOBO',
            'price' => '295',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'TABLEYA CAKE',
            'price' => '120',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'BROWNIE A LA MODE',
            'price' => '130',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'FLOURLESS CHOCOLATE CAKE ',
            'price' => '155',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'MOLTEN LAVA CAKE',
            'price' => '185',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'PROFITEROL',
            'price' => '120',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'BAKERY PLATTER',
            'price' => '210',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'CHOCOLATE PANNA COTTE',
            'price' => '95',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'TOASTED BREAD BIG',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'TOASTED BREAD SMALL',
            'price' => '70',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'CIABATA',
            'price' => '185',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'WHLE WHEAT BREAD',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'DANISH',
            'price' => '20',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'ENSAIMADA PLAIN',
            'price' => '25',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'ENSAIMADA UBE',
            'price' => '30',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'PASTRY',
            'name' => 'DONUT',
            'price' => '25',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'SALAD',
            'name' => 'SALAD DABAWENYO',
            'price' => '145',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SALAD',
            'name' => 'MALAGOS FARMHOUSE',
            'price' => '145',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'BURGER',
            'name' => 'MALAGOS CHEESE BURGER',
            'price' => '245',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SANDWICH',
            'name' => 'CLUBHOUSE SANDWICH',
            'price' => '205',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'CHEESE PLATTER',
            'name' => 'HERD`S PLATTER',
            'price' => '495',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'BOLOGNESE',
            'price' => '220',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'HAM AND BACON CARBONARA',
            'price' => '220',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'SEAFOOD AGLIO OLIO',
            'price' => '235',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'BIRDWATCHER',
            'price' => '365',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'DEEP DIVER',
            'price' => '375',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'BEER LOVER',
            'price' => '375',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'PASTA',
            'name' => 'CHEESE MAKER',
            'price' => '435',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SOUP',
            'name' => 'TINOLANG MANOK',
            'price' => '225',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SOUP',
            'name' => 'TINOLANG ISDA',
            'price' => '275',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SOUP',
            'name' => 'SINIGANG NA ISDA',
            'price' => '275',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'SOUP',
            'name' => 'BULALO SOUP',
            'price' => '285',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'GINATAANG GULA W/SHRIMP',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'GINATAANG GULA W/PORK',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'PINAKBET',
            'price' => '195',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ], [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'CHOPSUEY',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'KARE KARE GULAY',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'ADOBONG KANGKONG',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Food',
            'subcategory' => 'VEGETABLES',
            'name' => 'LAW-UY',
            'price' => '275',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		   [
            'category' => 'Beverages',
            'subcategory' => 'BEER',
            'name' => 'RED HORSE BEER STALLION',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'BEER',
            'name' => 'SAN MIGUEL FLAVORED BEER',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'BEER',
            'name' => 'SAN MIGUEL LIGHT BEER',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'BEER',
            'name' => 'SAN MIGUEL PALE PILSEN BEER',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'BOTTLED WATER',
            'name' => 'NATURE SPRING',
            'price' => '40',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'CHOCOLATE DRINKS',
            'name' => 'CHOCOLATE EH',
            'price' => '110',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'CHOCO DRINKS',
            'name' => 'MALAGOS HOUSE BLEND',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'BREWED COFFEE',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE AFFOGATO HOT',
            'price' => '10',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE AMERICANO HOT',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE AMERICANO COLD',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE LATTE HOT',
            'price' => '110',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE LATTE COLD',
            'price' => '120',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE MOCHA HOT',
            'price' => '120',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAFE MOCHA COLD',
            'price' => '150',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAPPUCCINO HOT',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CAPPUCCINO COLD',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CARAMEL MACCHIATO HOT',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'CARAMEL MACCHIATO COLD',
            'price' => '150',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'NESCAFE 3 IN 1',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'COFFEE',
            'name' => 'NESCAFE STICK',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE FRESH',
            'name' => 'KALAMANSI JUICE',
            'price' => '90',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE FRESH',
            'name' => 'MANGO JUICE',
            'price' => '90',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE FRESH',
            'name' => 'PINEAPPLE JUICE',
            'price' => '90',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE FRESH',
            'name' => 'WATERMELON JUICE',
            'price' => '90',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE FRESH',
            'name' => 'MANGO NECTAR',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE TETRA',
            'name' => 'CHOICE',
            'price' => '10',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'JUIE TETRA',
            'name' => 'ZEST-O',
            'price' => '10',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'APPLE CUCUMBER',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'AVOCADO SHAKE',
            'price' => '140',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'CHOCOLATE FREEZE',
            'price' => '170',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'COOKIES AND CREAM FREEZ ',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'MAIN CON YELO FREEZ',
            'price' => '180',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'MANGO SHAKE',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'PINEAPPLE SHAKE',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'VANILLA FREEZ',
            'price' => '160',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'WATERMELON SHAKE',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SHAKES',
            'name' => 'COFFEE JELLY FREEZE',
            'price' => '160',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'COKE BOTTLE',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'COKE CAN',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'COKE LIGHT',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'COKE ZERO',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'RTO BOTTLE',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'RTO CAN',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'SPRITE BOTTLE',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'SPRITE CAN',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'CALI',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'GATORADE',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'C2',
            'price' => '55',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'SODA',
            'name' => 'REAL LEAF',
            'price' => '80',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
		  [
            'category' => 'Beverages',
            'subcategory' => 'TEA',
            'name' => 'CAMOMILE TEA',
            'price' => '100',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'TEA',
            'name' => 'CGREEN TEA',
            'price' => '65',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'TEA',
            'name' => 'ICE TEA IN GLASS',
            'price' => '65',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'TEA',
            'name' => 'ICE TEA IN PITCHER',
            'price' => '120',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'TEA',
            'name' => 'LIPTON TEA',
            'price' => '35',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'WINE',
            'name' => 'CHIANTE',
            'price' => '895',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'WINE',
            'name' => 'MAISON',
            'price' => '795',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'WINE',
            'name' => 'MERLOT',
            'price' => '980',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'WINE',
            'name' => 'PROXIMO',
            'price' => '725',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Beverages',
            'subcategory' => 'WINE',
            'name' => 'TERRA VEGA',
            'price' => '750',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'FRESH CHEVRE',
            'price' => '210',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'FRESH FETA',
            'price' => '435',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'MANGO SUBLIME',
            'price' => '435',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'PINEAPPLE SUBLIME',
            'price' => '225',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
[
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'BLUSH',
            'price' => '395',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],			  
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHEESE',
            'name' => 'KESONG PUTI',
            'price' => '325',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 25G',
            'price' => '70',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 100G',
            'price' => '175',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 500G',
            'price' => '350',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '65% DARK CHOCOLATE 1000G',
            'price' => '610',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 100G',
            'price' => '200',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '72% DARK CHOCOLATE 500G',
            'price' => '375',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '672% DARK CHOCOLATE 1000G',
            'price' => '630',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 100G',
            'price' => '250',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
[
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => '85% DARK CHOCOLATE 500',
            'price' => '400',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],
[
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'TABLEYA 153G',
            'price' => '175',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],			  
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'TABLEYA CAN',
            'price' => '315',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'TABLEYA 500G',
            'price' => '420',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'CHOCOLATE',
            'name' => 'TABLEYA 1000G',
            'price' => '700',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  [
            'category' => 'Sundry',
            'subcategory' => 'SOUVENIR',
            'name' => 'GIFT BAG',
            'price' => '50',
            'restaurant_id' => 1,
            'is_prepared' => 1
          ],	
		  
		  

        ]
      );
    }
}
