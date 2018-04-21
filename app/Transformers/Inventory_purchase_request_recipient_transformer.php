<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\User_transformer;

class Inventory_purchase_request_recipient_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['user'];
    public function transform($purchase_request_recipient)
    {
        return [
            'id' => $purchase_request_recipient->id,
            'user_id' => (int)$purchase_request_recipient->user_id,
            'allow_approve' => $purchase_request_recipient->allow_approve == 1,
            'notify_email' => $purchase_request_recipient->notify_email == 1,
        ];
    }

    public function includeUser($purchase_request_recipient)
    {
        if ($purchase_request_recipient->user) {
            return $this->item($purchase_request_recipient->user, new User_transformer);
        }
    }
}
