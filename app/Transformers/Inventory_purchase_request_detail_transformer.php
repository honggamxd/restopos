<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_purchase_request_detail
use App\Transformers\Inventory_purchase_request_transformer;
class Inventory_purchase_request_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_purchase_request','inventory_item'];
    public function transform($Purchse_request_detail)
    {
        return [	
            'id' => $Purchse_request_detail->id,
            'inventory_purchase_request_id' => $Purchse_request_detail->inventory_purchase_request_id,
            'inventory_item_id' => $Purchse_request_detail->inventory_item_id,
            'balance_on_hand' => $Purchse_request_detail->balance_on_hand,
            'quantity' => $Purchse_request_detail->quantity,
            'unit_price' => $Purchse_request_detail->unit_price,
        ];
    }

    public function includeInventoryPurchaseRequest ($Purchse_request_detail)
    {
        if ($Purchse_request_detail->inventory_purchase_request) {
            return $this->item($Purchse_request_detail->inventory_purchase_request, new Inventory_purchase_request_transformer);
        }
    }

    public function includeInventoryItem ($Purchse_request_detail)
    {
        if ($Purchse_request_detail->inventory_item) {
            return $this->item($Purchse_request_detail->inventory_item, new Inventory_item_transformer);
        }
    }
}
