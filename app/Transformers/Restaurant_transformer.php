<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class Restaurant_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($restaurant)
    {
        return [
            'id' => $restaurant->id,
            'name' => $restaurant->name,
        ];
    }
}
