<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class User_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($user)
    {
        return [
            //
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email_address' => $user->email_address,
            'privilege' => $user->privilege,
            'restaurant_id' => $user->restaurant_id,
            'allow_edit_info' => $user->allow_edit_info,
        ];
    }
}
