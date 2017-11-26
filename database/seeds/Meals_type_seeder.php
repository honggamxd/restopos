<?php

use Illuminate\Database\Seeder;
use App\Restaurant_meal_types;
use Carbon\Carbon;

class Meals_type_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meal_type_list = array();
        $meal_type_list = 
        [
            [
              'type' => 'Breakfast',
              'schedule' => Carbon::create(null,null,null,6,1,0),
              'restaurant_id' => '1',
            ],
            [
              'type' => 'Lunch',
              'schedule' => Carbon::create(null,null,null,10,1,0),
              'restaurant_id' => '1',
            ],
            [
              'type' => 'PM Snacks',
              'schedule' => Carbon::create(null,null,null,14,1,0),
              'restaurant_id' => '1',
            ],
            [
              'type' => 'Dinner',
              'schedule' => Carbon::create(null,null,null,18,1,0),
              'restaurant_id' => '1',
            ],
            [
              'type' => 'Breakfast',
              'schedule' => Carbon::create(null,null,null,6,1,0),
              'restaurant_id' => '2',
            ],
            [
              'type' => 'Lunch',
              'schedule' => Carbon::create(null,null,null,10,1,0),
              'restaurant_id' => '2',
            ],
            [
              'type' => 'PM Snacks',
              'schedule' => Carbon::create(null,null,null,14,1,0),
              'restaurant_id' => '2',
            ],
            [
              'type' => 'Dinner',
              'schedule' => Carbon::create(null,null,null,18,1,0),
              'restaurant_id' => '2',
            ],
            [
              'type' => 'Breakfast',
              'schedule' => Carbon::create(null,null,null,6,1,0),
              'restaurant_id' => '3',
            ],
            [
              'type' => 'Lunch',
              'schedule' => Carbon::create(null,null,null,10,1,0),
              'restaurant_id' => '3',
            ],
            [
              'type' => 'PM Snacks',
              'schedule' => Carbon::create(null,null,null,14,1,0),
              'restaurant_id' => '3',
            ],
            [
              'type' => 'Dinner',
              'schedule' => Carbon::create(null,null,null,18,1,0),
              'restaurant_id' => '3',
            ]
        ];
        foreach ($meal_type_list as $meal_type_data) {
          $meal_type = new Restaurant_meal_types;
          $meal_type->type = $meal_type_data['type'];
          $meal_type->schedule = $meal_type_data['schedule'];
          $meal_type->restaurant_id = $meal_type_data['restaurant_id'];
          $meal_type->save();
          echo "\n".$meal_type_data['type'].' - '.$meal_type_data['schedule'];
        }
    }
}
