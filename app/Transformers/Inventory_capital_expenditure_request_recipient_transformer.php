<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\User_transformer;

class Inventory_capital_expenditure_request_recipient_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['user'];
    public function transform($capital_expenditure_request_recipient)
    {
        return [
            'id' => $capital_expenditure_request_recipient->id,
            'user_id' => (int)$capital_expenditure_request_recipient->user_id,
            'allow_approve' => $capital_expenditure_request_recipient->allow_approve == 1,
            'notify_email' => $capital_expenditure_request_recipient->notify_email == 1,
        ];
    }

    public function includeUser($capital_expenditure_request_recipient)
    {
        if ($capital_expenditure_request_recipient->user) {
            return $this->item($capital_expenditure_request_recipient->user, new User_transformer);
        }
    }
}
