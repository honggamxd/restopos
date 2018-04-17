<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_purchase_request

use App\Transformers\Inventory_purchase_request_detail_transformer;

class Inventory_purchase_request_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details'];
    public function transform($Purchase_request)
    {
        return [
            'id'=> $Purchase_request->id,
            'uuid'=> $Purchase_request->uuid,
            'purchase_request_date'=> $Purchase_request->purchase_request_date,
            'purchase_request_date_formatted'=> Carbon::parse($Purchase_request->purchase_request_date)->format('F d, Y'),
            'purchase_request_number'=> (integer)$Purchase_request->purchase_request_number,
            'purchase_request_number_formatted'=> sprintf("%05d",$Purchase_request->purchase_request_number),
            'requesting_department' => $Purchase_request->requesting_department,
            'reason_for_the_request' => $Purchase_request->reason_for_the_request,
            'request_chargeable_to' => $Purchase_request->request_chargeable_to,
            'type_of_item_requested' => $Purchase_request->type_of_item_requested,
            'date_needed' => $Purchase_request->date_needed,
            'date_needed_formatted' => Carbon::parse($Purchase_request->date_needed)->format('F d, Y'),
            'requested_by_name' => $Purchase_request->requested_by_name,
            'requested_by_date' => $Purchase_request->requested_by_date,
            'noted_by_name' => $Purchase_request->noted_by_name,
            'noted_by_date' => $Purchase_request->noted_by_date,
            'is_approved' => $Purchase_request->is_approved == 1,
            'approved_by_name' => $Purchase_request->approved_by_name,
            'approved_by_date' => $Purchase_request->approved_by_date,
            'inventory_receiving_report_id' => $Purchase_request->inventory_receiving_report_id,
            'inventory_capital_expenses_request_form_id' => $Purchase_request->inventory_capital_expenses_request_form_id,
            'inventory_purchase_order_id' => $Purchase_request->inventory_purchase_order_id,
            'form' => route('inventory.purchase-request.index',[$Purchase_request->uuid]),
        ];
    }

    public function includeDetails ($Purchase_request)
    {
        if ($Purchase_request->details) {
            return $this->collection($Purchase_request->details, new Inventory_purchase_request_detail_transformer);
        }
    }
}
