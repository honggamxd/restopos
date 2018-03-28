<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_purchase_order_detail
use App\Transformers\Inventory_purchase_order_transformer;
class Inventory_purchase_order_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_purchase_order','inventory_item'];
    public function transform($purchase_order_detail)
    {
        return [	
            'id' => $purchase_order_detail->id,
            'inventory_purchase_order_id' => $purchase_order_detail->inventory_purchase_order_id,
            'inventory_item_id' => $purchase_order_detail->inventory_item_id,
            'balance_on_hand' => (integer)$purchase_order_detail->balance_on_hand,
            'quantity' => (integer)$purchase_order_detail->quantity,
            'unit_price' => (float)$purchase_order_detail->unit_price,
        ];
    }

    public function includeInventoryPurchaseOrder($purchase_order_detail)
    {
        if ($purchase_order_detail->inventory_purchase_order) {
            return $this->item($purchase_order_detail->inventory_purchase_order, new Inventory_purchase_order_transformer);
        }
    }

    public function includeInventoryItem($purchase_order_detail)
    {
        if ($purchase_order_detail->inventory_item) {
            return $this->item($purchase_order_detail->inventory_item, new Inventory_item_transformer);
        }
    }
}
