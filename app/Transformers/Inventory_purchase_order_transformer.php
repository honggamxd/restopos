<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_purchase_order

use App\Transformers\Inventory_purchase_order_detail_transformer;

class Inventory_purchase_order_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details','purchase_request'];
    public function transform($purchase_order)
    {
        return [
            'id'=> $purchase_order->id,
            'uuid'=> $purchase_order->uuid,
            'purchase_order_date'=> $purchase_order->purchase_order_date,
            'purchase_order_date_formatted'=> Carbon::parse($purchase_order->purchase_order_date)->format('F d, Y'),
            'purchase_order_number'=> (integer)$purchase_order->purchase_order_number,
            'purchase_order_number_formatted'=> sprintf("%05d",$purchase_order->purchase_order_number),
            'supplier_name' => $purchase_order->supplier_name,
            'supplier_address' => $purchase_order->supplier_address,
            'supplier_tin' => $purchase_order->supplier_tin,
            'term' => $purchase_order->term,
            'requesting_department' => $purchase_order->requesting_department,
            'purpose' => $purchase_order->purpose,
            'request_chargeable_to' => $purchase_order->request_chargeable_to,
            'requested_by_name' => $purchase_order->requested_by_name,
            'requested_by_date' => $purchase_order->requested_by_date,
            'noted_by_name' => $purchase_order->noted_by_name,
            'noted_by_date' => $purchase_order->noted_by_date,
            'approved_by_name' => $purchase_order->approved_by_name,
            'approved_by_date' => $purchase_order->approved_by_date,
            'inventory_purchase_request_id' => $purchase_order->inventory_purchase_request_id,
            'form' => route('inventory.purchase-order.index',[$purchase_order->uuid]),
        ];
    }

    public function includeDetails ($purchase_order)
    {
        if ($purchase_order->details) {
            return $this->collection($purchase_order->details, new Inventory_purchase_order_detail_transformer);
        }
    }

    public function includePurchaseRequest($purchase_order)
    {
        if ($purchase_order->inventory_purchase_request) {
            return $this->item( $purchase_order->inventory_purchase_request,  new Inventory_purchase_request_transformer());
        }
    }
}
