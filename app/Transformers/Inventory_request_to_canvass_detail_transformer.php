<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

//Inventory_request_to_canvass_detail
use App\Transformers\Inventory_purchase_request_transformer;
class Inventory_request_to_canvass_detail_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['inventory_request_to_canvass','inventory_item'];
    public function transform($request_to_canvass_detail)
    {
        return [	
            'id' => $request_to_canvass_detail->id,
            'inventory_request_to_canvass_id' => $request_to_canvass_detail->inventory_request_to_canvass_id,
            'inventory_item_id' => $request_to_canvass_detail->inventory_item_id,
            'quantity' => (integer)$request_to_canvass_detail->quantity,
            'vendor_1_unit_price' => (float)$request_to_canvass_detail->vendor_1_unit_price,
            'vendor_2_unit_price' => (float)$request_to_canvass_detail->vendor_2_unit_price,
            'vendor_3_unit_price' => (float)$request_to_canvass_detail->vendor_3_unit_price,
        ];
    }

    public function includeInventoryRequestToCanvass ($request_to_canvass_detail)
    {
        if ($request_to_canvass_detail->inventory_request_to_canvass) {
            return $this->item($request_to_canvass_detail->inventory_request_to_canvass, new Inventory_request_to_canvass_transformer);
        }
    }

    public function includeInventoryItem ($request_to_canvass_detail)
    {
        if ($request_to_canvass_detail->inventory_item) {
            return $this->item($request_to_canvass_detail->inventory_item, new Inventory_item_transformer);
        }
    }
}
