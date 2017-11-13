<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class App_config_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('app_config')->where('id',1)->update(
        ['settlements' => 'cash,credit,debit,cheque,guest_ledger,send_bill,free_of_charge,manager_meals,sales_office,representation',
        'badorder_settlements' => 'cancelled,bad_order,staff_charge',
        'settlements_arrangements' => 'cash,credit,debit,cheque,guest_ledger,send_bill,free_of_charge,manager_meals,sales_office,representation,cancelled'
        ]
      );
    }
}
