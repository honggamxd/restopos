<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\Inventory_item_detail_transformer;

class Inventory_item_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details'];
    public function transform($Inventory_item)
    {
        return [
            'id' => $Inventory_item->id,
            'uuid' => $Inventory_item->uuid,
            'item_name' => $Inventory_item->name,
            'category' => $Inventory_item->category,
            'subcategory' => $Inventory_item->subcategory,
            'unit_of_measure' => $Inventory_item->unit_of_measure,
            'average_cost' => (float)$Inventory_item->average_cost,
            'total_quantity' => (integer)$Inventory_item->total_quantity,
        ];
    }

    public function includeDetails ($Inventory_item)
    {
        if ($Inventory_item->details) {
            return $this->collection($Inventory_item->details, new ItemDetailTransformer());
        }
    }
}
