<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_capital_expenditure_request_detail
use App\Transformers\Inventory_purchase_request_transformer;
class Inventory_capital_expenditure_request_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_capital_expenditure_request','inventory_item'];
    public function transform($capital_expenditure_request_detail)
    {
        return [	
            'id' => $capital_expenditure_request_detail->id,
            'inventory_capital_expenditure_request_id' => $capital_expenditure_request_detail->inventory_capital_expenditure_request_id,
            'inventory_item_id' => $capital_expenditure_request_detail->inventory_item_id,
            'quantity' => (integer)$capital_expenditure_request_detail->quantity,
            'unit_price' => (float)$capital_expenditure_request_detail->unit_price,
        ];
    }

    public function includeInventoryCapitalExpenditureRequestForm ($capital_expenditure_request_detail)
    {
        if ($capital_expenditure_request_detail->inventory_capital_expenditure_request) {
            return $this->item($capital_expenditure_request_detail->inventory_capital_expenditure_request, new Inventory_capital_expenditure_request_transformer);
        }
    }

    public function includeInventoryItem ($capital_expenditure_request_detail)
    {
        if ($capital_expenditure_request_detail->inventory_item) {
            return $this->item($capital_expenditure_request_detail->inventory_item, new Inventory_item_transformer);
        }
    }
}
