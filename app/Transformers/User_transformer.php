<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\Restaurant_transformer;
use App\Transformers\Inventory_user_permission_transformer;

class User_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['restaurant','permissions'];
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
            case 'inventory_user':
                $str_privilege = "Inventory User";
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
            'position' => $user->position,
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

    public function includePermissions($user)
    {
        if ($user->permissions) {
            return $this->item($user->permissions, new Inventory_user_permission_transformer);
        }
    }
}
