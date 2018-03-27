<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\Inventory_item_transformer;

class Inventory_item_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['item'];
    public function transform($Inventory_item_detail)
    {
        return [
            'id' => $Inventory_item_detail->id,
            'inventory_item_id' => $Inventory_item_detail->inventory_item_id,
            'unit_cost' => (float)$Inventory_item_detail->unit_cost,
            'quantity' => (integer)$Inventory_item_detail->quantity,
        ];
    }

    public function includeItem ($Inventory_item_detail)
    {
        if ($Inventory_item_detail->item) {
            return $this->item( $Inventory_item_detail->item,  new Inventory_item_transformer());
        }
    }

}
