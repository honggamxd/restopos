<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\Restaurant_transformer;

class User_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['restaurant'];
    public function transform($user)
    {
        switch ($user->privilege) {
            case 'admin':
                $str_privilege = "Admin";
                break;
            case 'restaurant_admin':
                $str_privilege = "Restaurant Admin";
                break;
            case 'restaurant_cashier':
                $str_privilege = "Restaurant Cashier";
                break;
            default:
                $str_privilege = "";
                break;
        }
        return [
            //
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email_address' => $user->email_address,
            'privilege' => $user->privilege,
            'restaurant_id' => $user->restaurant_id,
            'str_privilege' => $str_privilege,
            'restaurant_id' => $user->restaurant_id,
            'allow_edit_info' => $user->allow_edit_info,
        ];
    }

    public function includeRestaurant($user)
    {
        if ($user->restaurant) {
            return $this->item($user->restaurant, new Restaurant_transformer);
        }
    }
}
