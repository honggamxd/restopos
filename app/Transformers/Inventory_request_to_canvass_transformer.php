<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_request_to_canvass

use App\Transformers\Inventory_request_to_canvass_detail_transformer;

class Inventory_request_to_canvass_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details'];
    public function transform($request_to_canvass)
    {
        return [
            'id'=> $request_to_canvass->id,
            'uuid'=> $request_to_canvass->uuid,
            'request_to_canvass_date'=> $request_to_canvass->request_to_canvass_date,
            'request_to_canvass_date_formatted'=> Carbon::parse($request_to_canvass->request_to_canvass_date)->format('F d, Y'),
            'request_to_canvass_number'=> (integer)$request_to_canvass->request_to_canvass_number,
            'request_to_canvass_number_formatted'=> sprintf("%05d",$request_to_canvass->request_to_canvass_number),
            'requesting_department' => $request_to_canvass->requesting_department,
            'reason_for_the_request' => $request_to_canvass->reason_for_the_request,
            'type_of_item_requested' => $request_to_canvass->type_of_item_requested,
            'inventory_purchase_request_id' => $request_to_canvass->inventory_purchase_request_id,
            'requested_by_name' => $request_to_canvass->requested_by_name,
            'requested_by_date' => $request_to_canvass->requested_by_date,
            'noted_by_name' => $request_to_canvass->noted_by_name,
            'noted_by_date' => $request_to_canvass->noted_by_date,
            'canvass_by_name' => $request_to_canvass->canvass_by_name,
            'canvass_by_date' => $request_to_canvass->canvass_by_date,
            'vendor_1_name' => $request_to_canvass->vendor_1_name,
            'vendor_2_name' => $request_to_canvass->vendor_2_name,
            'vendor_3_name' => $request_to_canvass->vendor_3_name,
            'inventory_capital_expenses_request_form_id' => $request_to_canvass->inventory_capital_expenses_request_form_id,
            'form' => route('inventory.request-to-canvass.index',[$request_to_canvass->uuid]),
        ];
    }

    public function includeDetails ($request_to_canvass)
    {
        if ($request_to_canvass->details) {
            return $this->collection($request_to_canvass->details, new Inventory_request_to_canvass_detail_transformer);
        }
    }
}
