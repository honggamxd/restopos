<?php

use Illuminate\Database\Seeder;

class Parks_office extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
      DB::table('restaurant')->insert([
          'name' => 'Parks Office',
      ]);
      DB::table('user')->insert(
        [
          [
            'username' => 'admin4',
            'name' => 'admin4 name',
            'password' => md5('admin4'),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 4
          ],
          [
            'username' => 'user4',
            'name' => 'user4 name',
            'password' => md5('user4'),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 4
          ],
        ]
      );
      DB::table('issuance_to')->insert(
        [
          ['name' => 'Parks Office','ref_id'=>4,'ref_table'=>'restaurant']
        ]
      );
    }
}
