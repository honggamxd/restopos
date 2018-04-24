<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\User_transformer;

class Inventory_stock_issuance_recipient_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['user'];
    public function transform($stock_issuance_recipient)
    {
        return [
            'id' => $stock_issuance_recipient->id,
            'user_id' => (int)$stock_issuance_recipient->user_id,
            'allow_approve' => $stock_issuance_recipient->allow_approve == 1,
            'notify_email' => $stock_issuance_recipient->notify_email == 1,
        ];
    }

    public function includeUser($stock_issuance_recipient)
    {
        if ($stock_issuance_recipient->user) {
            return $this->item($stock_issuance_recipient->user, new User_transformer);
        }
    }
}
