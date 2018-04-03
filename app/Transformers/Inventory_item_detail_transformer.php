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
    protected $availableIncludes = ['item','inventory_receiving_report','inventory_stock_issuance'];
    public function transform($Inventory_item_detail)
    {
        return [
            'id' => $Inventory_item_detail->id,
            'inventory_item_id' => $Inventory_item_detail->inventory_item_id,
            'unit_cost' => (float)$Inventory_item_detail->unit_cost,
            'quantity' => (integer)$Inventory_item_detail->quantity,
            'inventory_receiving_report_id' => $Inventory_item_detail->inventory_receiving_report_id,
            'inventory_stock_issuance_id' => $Inventory_item_detail->inventory_stock_issuance_id,
        ];
    }

    public function includeItem ($Inventory_item_detail)
    {
        if ($Inventory_item_detail->item) {
            return $this->item( $Inventory_item_detail->item,  new Inventory_item_transformer());
        }
    }

    public function includeInventoryReceivingReport ($Inventory_item_detail)
    {
        if ($Inventory_item_detail->inventory_receiving_report) {
            return $this->item( $Inventory_item_detail->inventory_receiving_report,  new Inventory_item_transformer());
        }
    }

    public function includeInventoryStockIssuance ($Inventory_item_detail)
    {
        if ($Inventory_item_detail->inventory_stock_issuance) {
            return $this->item( $Inventory_item_detail->inventory_stock_issuance,  new Inventory_item_transformer());
        }
    }

}
