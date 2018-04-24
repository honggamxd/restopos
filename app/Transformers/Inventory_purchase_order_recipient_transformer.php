<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\User_transformer;

class Inventory_purchase_order_recipient_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['user'];
    public function transform($purchase_order_recipient)
    {
        return [
            'id' => $purchase_order_recipient->id,
            'user_id' => (int)$purchase_order_recipient->user_id,
            'allow_approve' => $purchase_order_recipient->allow_approve == 1,
            'notify_email' => $purchase_order_recipient->notify_email == 1,
        ];
    }

    public function includeUser($purchase_order_recipient)
    {
        if ($purchase_order_recipient->user) {
            return $this->item($purchase_order_recipient->user, new User_transformer);
        }
    }
}
