<?php

use Illuminate\Database\Seeder;

class Front_office_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
      DB::table('restaurant')->insert([
          'name' => 'Front Office',
      ]);
      DB::table('user')->insert(
        [
          [
            'username' => 'admin5',
            'name' => 'admin5 name',
            'password' => bcrypt(md5('admin5')),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 5
          ],
          [
            'username' => 'user5',
            'name' => 'user5 name',
            'password' => bcrypt(md5('user5')),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 5
          ],
        ]
      );
      DB::table('issuance_to')->insert(
        [
          ['name' => 'Parks Office','ref_id'=>5,'ref_table'=>'restaurant']
        ]
      );
    }
}
