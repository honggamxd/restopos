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
            'name' => 'admin',
            'password' => md5('admin'),
            'privilege' => 'admin',
            'restaurant_id' => 0
          ],
          [
            'username' => 'user1',
            'name' => 'user1',
            'password' => md5('user1'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 1
          ],
          [
            'username' => 'user2',
            'name' => 'user2',
            'password' => md5('user2'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 2
          ],
          [
            'username' => 'user3',
            'name' => 'user3',
            'password' => md5('user3'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 3
          ]
        ]
      );

      DB::table('issuance_to')->insert(
        [
          ['name' => 'Viewdeck Café','ref_id'=>1,'ref_table'=>'restaurant'],
          ['name' => 'Koi Café','ref_id'=>2,'ref_table'=>'restaurant'],
          ['name' => 'Roberto’s Garden Restaurant','ref_id'=>3,'ref_table'=>'restaurant']
        ]
      );

      DB::table('restaurant_menu')->insert(
        [
          ['restaurant_id' => 1,'is_prepared'=> 1],
          ['restaurant_id' => 2,'is_prepared'=> 1],
          ['restaurant_id' => 3,'is_prepared'=> 1]
        ]
      );


    }
}
