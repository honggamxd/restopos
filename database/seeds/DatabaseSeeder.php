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
    }
}
