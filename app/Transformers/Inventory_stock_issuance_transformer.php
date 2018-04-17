<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_stock_issuance

use App\Transformers\Inventory_stock_issuance_detail_transformer;
use App\Transformers\Inventory_receiving_report_transformer;

class Inventory_stock_issuance_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details','inventory_receiving_report'];
    public function transform($stock_issuance)
    {
        return [
            'id'=> $stock_issuance->id,
            'uuid'=> $stock_issuance->uuid,
            'stock_issuance_date'=> $stock_issuance->stock_issuance_date,
            'stock_issuance_date_formatted'=> Carbon::parse($stock_issuance->stock_issuance_date)->format('F d, Y'),
            'stock_issuance_number'=> (integer)$stock_issuance->stock_issuance_number,
            'stock_issuance_number_formatted'=> sprintf("%05d",$stock_issuance->stock_issuance_number),
            'requesting_department' => $stock_issuance->requesting_department,
            'request_chargeable_to' => $stock_issuance->request_chargeable_to,
            'supplier_name' => $stock_issuance->supplier_name,
            'supplier_address' => $stock_issuance->supplier_address,
            'supplier_tin' => $stock_issuance->supplier_tin,
            'received_by_name' => $stock_issuance->received_by_name,
            'received_by_date' => $stock_issuance->received_by_date,
            'issued_by_name' => $stock_issuance->issued_by_name,
            'issued_by_date' => $stock_issuance->issued_by_date,
            'is_approved' => $stock_issuance->is_approved == 1,
            'approved_by_name' => $stock_issuance->approved_by_name,
            'approved_by_date' => $stock_issuance->approved_by_date,
            'posted_by_name' => $stock_issuance->posted_by_name,
            'posted_by_date' => $stock_issuance->posted_by_date,
            'inventory_receiving_report_id' => $stock_issuance->inventory_receiving_report_id,
            'form' => route('inventory.stock-issuance.index',[$stock_issuance->uuid]),
        ];
    }

    public function includeDetails ($stock_issuance)
    {
        if ($stock_issuance->details) {
            return $this->collection($stock_issuance->details, new Inventory_stock_issuance_detail_transformer);
        }
    }

    public function includeInventoryReceivingReport($stock_issuance)
    {
        if ($stock_issuance->inventory_receiving_report) {
            return $this->item($stock_issuance->inventory_receiving_report, new Inventory_receiving_report_transformer);
        }
    }
}
