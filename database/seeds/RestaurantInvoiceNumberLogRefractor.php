<?php

use Illuminate\Database\Seeder;
use App\Restaurant_bill;
use App\Restaurant_invoice_number_log;

class RestaurantInvoiceNumberLogRefractor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurants = Restaurant_bill::all();
        foreach ($restaurants as $restaurant) {
          $Restaurant_invoice_number_log = new Restaurant_invoice_number_log;
          $Restaurant_invoice_number_log->invoice_number = $restaurant->invoice_number;
          $Restaurant_invoice_number_log->user_id = $restaurant->cashier_id;
          $Restaurant_invoice_number_log->restaurant_bill_id = $restaurant->id;
          $Restaurant_invoice_number_log->save();

          echo "\n".$Restaurant_invoice_number_log->restaurant_bill_id.' - '.$Restaurant_invoice_number_log->invoice_number;
        }
    }
}
