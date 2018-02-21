<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('app_config')->where('id',1)->insert(
        ['settlements' => 'cash,credit,debit,cheque,guest_ledger,send_bill,free_of_charge,bod,manager_meals,sales_office,representation,staff_charge,package_inclusion',
        'badorder_settlements' => 'cancelled,bad_order,staff_charge',
        'settlements_arrangements' => 'cash,credit,debit,cheque,sales_office,guest_ledger,send_bill,free_of_charge,bod,manager_meals,representation,staff_charge,package_inclusion,cancelled',
        'categories' => 'Rooms,Food,Beverages,Parks,Sundry,Others'
        ]
      );

      $user = new User;
      $user->privilege = 'admin';
      $user->name = 'admin';
      $user->username = 'admin';
      $user->password = bcrypt(md5('admin'));
      $user->save();
      $this->call(App_config_seeder::class);
      $this->call(Meals_type_seeder::class);
    }
}
