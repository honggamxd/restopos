<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_stock_issuance_detail
use App\Transformers\Inventory_receiving_report_transformer;
class Inventory_stock_issuance_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_receiving_report','inventory_item'];
    public function transform($stock_issuance_detail)
    {
        return [	
            'id' => $stock_issuance_detail->id,
            'inventory_stock_issuance_id_detail' => $stock_issuance_detail->inventory_stock_issuance_id_detail,
            'inventory_item_id' => $stock_issuance_detail->inventory_item_id,
            'quantity' => (integer)$stock_issuance_detail->quantity,
            'unit_price' => (float)$stock_issuance_detail->unit_price,
            'remarks' => $stock_issuance_detail->remarks,
        ];
    }

    public function includeInventoryStockIssuance($stock_issuance_detail)
    {
        if ($stock_issuance_detail->inventory_stock_issuance) {
            return $this->item($stock_issuance_detail->inventory_stock_issuance, new Inventory_stock_issuance_transformer);
        }
    }

    public function includeInventoryItem ($stock_issuance_detail)
    {
        if ($stock_issuance_detail->inventory_item) {
            return $this->item($stock_issuance_detail->inventory_item, new Inventory_item_transformer);
        }
    }
}
