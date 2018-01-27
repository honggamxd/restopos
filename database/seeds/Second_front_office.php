<?php

use Illuminate\Database\Seeder;

class Second_front_office extends Seeder
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
            'username' => 'admin6',
            'name' => 'admin6 name',
            'password' => bcrypt(md5('admin6')),
            'privilege' => 'restaurant_admin',
            'restaurant_id' => 6
          ],
          [
            'username' => 'user6',
            'name' => 'user6 name',
            'password' => bcrypt(md5('user6')),
            'privilege' => 'restaurant_cashier',
            'restaurant_id' => 6
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
