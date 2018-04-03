<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_receiving_report

use App\Transformers\Inventory_receiving_report_detail_transformer;

class Inventory_receiving_report_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details','inventory_purchase_order'];
    public function transform($receiving_report)
    {
        return [
            'id'=> $receiving_report->id,
            'uuid'=> $receiving_report->uuid,
            'receiving_report_date'=> $receiving_report->receiving_report_date,
            'receiving_report_date_formatted'=> Carbon::parse($receiving_report->receiving_report_date)->format('F d, Y h:i:s A'),
            'receiving_report_number'=> (integer)$receiving_report->receiving_report_number,
            'receiving_report_number_formatted'=> sprintf("%05d",$receiving_report->receiving_report_number),
            'supplier_name' => $receiving_report->supplier_name,
            'supplier_address' => $receiving_report->supplier_address,
            'supplier_tin' => $receiving_report->supplier_tin,
            'supplier_contact_number' => $receiving_report->supplier_contact_number,
            'term' => $receiving_report->term,
            'requesting_department' => $receiving_report->requesting_department,
            'purpose' => $receiving_report->purpose,
            'request_chargeable_to' => $receiving_report->request_chargeable_to,
            'received_by_name' => $receiving_report->received_by_name,
            'received_by_date' => $receiving_report->received_by_date,
            'checked_by_name' => $receiving_report->checked_by_name,
            'checked_by_date' => $receiving_report->checked_by_date,
            'posted_by_name' => $receiving_report->posted_by_name,
            'posted_by_date' => $receiving_report->posted_by_date,
            'inventory_purchase_order_id' => $receiving_report->inventory_purchase_order_id,
            'form' => route('inventory.receiving-report.index',[$receiving_report->uuid]),
        ];
    }

    public function includeDetails ($receiving_report)
    {
        if ($receiving_report->details) {
            return $this->collection($receiving_report->details, new Inventory_receiving_report_detail_transformer);
        }
    }

    public function includeInventoryPurchaseOrder($purchase_order)
    {
        if ($purchase_order->inventory_purchase_order) {
            return $this->item( $purchase_order->inventory_purchase_order,  new Inventory_purchase_order_transformer());
        }
    }
}
