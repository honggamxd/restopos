<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_receiving_report_detail
use App\Transformers\Inventory_receiving_report_transformer;
class Inventory_receiving_report_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_receiving_report','inventory_item'];
    public function transform($receiving_report)
    {
        return [	
            'id' => $receiving_report->id,
            'inventory_receiving_report_id' => $receiving_report->inventory_receiving_report_id,
            'inventory_item_id' => $receiving_report->inventory_item_id,
            'quantity' => (integer)$receiving_report->quantity,
            'unit_price' => (float)$receiving_report->unit_price,
            'remarks' => $receiving_report->remarks,
        ];
    }

    public function includeInventoryReceivingReport($receiving_report)
    {
        if ($receiving_report->inventory_receiving_report) {
            return $this->item($receiving_report->inventory_receiving_report, new Inventory_receiving_report_transformer);
        }
    }

    public function includeInventoryItem ($receiving_report)
    {
        if ($receiving_report->inventory_item) {
            return $this->item($receiving_report->inventory_item, new Inventory_item_transformer);
        }
    }
}
