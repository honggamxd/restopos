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
            'password' => md5('admin'),
            'privilege' => 'admin',
            'restaurant_id' => 0
          ],
          [
            'username' => 'user1',
            'password' => md5('user1'),
            'privilege' => 'restaurant',
            'restaurant_id' => 1
          ],
          [
            'username' => 'user2',
            'password' => md5('user2'),
            'privilege' => 'restaurant',
            'restaurant_id' => 2
          ],
          [
            'username' => 'user3',
            'password' => md5('user3'),
            'privilege' => 'restaurant',
            'restaurant_id' => 3
          ]
        ]
      );
    }
}
