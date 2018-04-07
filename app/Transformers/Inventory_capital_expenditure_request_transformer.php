<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Carbon\Carbon;
// Inventory_capital_expenditure_request

use App\Transformers\Inventory_capital_expenditure_request_detail_transformer;
use App\Transformers\Inventory_purchase_request_transformer;

class Inventory_capital_expenditure_request_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['details','inventory_purchase_request'];
    public function transform($capital_expenditure_request)
    {
        return [
            'id'=> $capital_expenditure_request->id,
            'uuid'=> $capital_expenditure_request->uuid,
            'capital_expenditure_request_date'=> $capital_expenditure_request->capital_expenditure_request_date,
            'capital_expenditure_request_date_formatted'=> Carbon::parse($capital_expenditure_request->capital_expenditure_request_date)->format('F d, Y'),
            'capital_expenditure_request_number'=> (integer)$capital_expenditure_request->capital_expenditure_request_number,
            'capital_expenditure_request_number_formatted'=> sprintf("%05d",$capital_expenditure_request->capital_expenditure_request_number),
            'budget_description' => $capital_expenditure_request->budget_description,
            'budget_amount' => (float)$capital_expenditure_request->budget_amount,
            'department' => $capital_expenditure_request->department,
            'source_of_funds' => $capital_expenditure_request->source_of_funds,
            'brief_project_description' => $capital_expenditure_request->brief_project_description,
            'purpose' => $capital_expenditure_request->purpose,
            'requested_by_name' => $capital_expenditure_request->requested_by_name,
            'requested_by_date' => $capital_expenditure_request->requested_by_date,
            'requested_by_date_formatted' => $capital_expenditure_request->requested_by_date!=null ? Carbon::parse($capital_expenditure_request->requested_by_date)->format('F d, Y') : '',
            'requested_by_position' => $capital_expenditure_request->requested_by_position,
            'approved_by_1_name' => $capital_expenditure_request->approved_by_1_name,
            'approved_by_1_date' => $capital_expenditure_request->approved_by_1_date,
            'approved_by_1_date_formatted' => $capital_expenditure_request->approved_by_1_date!=null ? Carbon::parse($capital_expenditure_request->approved_by_1_date)->format('F d, Y') : '',
            'approved_by_1_position' => $capital_expenditure_request->approved_by_1_position,
            'approved_by_2_name' => $capital_expenditure_request->approved_by_2_name,
            'approved_by_2_date' => $capital_expenditure_request->approved_by_2_date,
            'approved_by_2_date_formatted' => $capital_expenditure_request->approved_by_2_date!=null ? Carbon::parse($capital_expenditure_request->approved_by_2_date)->format('F d, Y') : '',
            'approved_by_2_position' => $capital_expenditure_request->approved_by_2_position,
            'verified_as_funded_by_name' => $capital_expenditure_request->verified_as_funded_by_name,
            'verified_as_funded_by_date' => $capital_expenditure_request->verified_as_funded_by_date,
            'verified_as_funded_by_date_formatted' => $capital_expenditure_request->verified_as_funded_by_date!=null ? Carbon::parse($capital_expenditure_request->verified_as_funded_by_date)->format('F d, Y') : '',
            'verified_as_funded_by_position' => $capital_expenditure_request->verified_as_funded_by_position,
            'recorded_by_name' => $capital_expenditure_request->recorded_by_name,
            'recorded_by_date' => $capital_expenditure_request->recorded_by_date,
            'recorded_by_date_formatted' => $capital_expenditure_request->recorded_by_date!=null ? Carbon::parse($capital_expenditure_request->recorded_by_date)->format('F d, Y') : '',
            'recorded_by_position' => $capital_expenditure_request->recorded_by_position,
            'inventory_purchase_request_id' => $capital_expenditure_request->inventory_purchase_request_id,
            'form' => route('inventory.capital-expenditure-request.index',[$capital_expenditure_request->uuid]),
        ];
    }

    public function includeDetails ($capital_expenditure_request)
    {
        if ($capital_expenditure_request->details) {
            return $this->collection($capital_expenditure_request->details, new Inventory_capital_expenditure_request_detail_transformer);
        }
    }

    public function includeInventoryPurchaseRequest($capital_expenditure_request)
    {
        if ($capital_expenditure_request->inventory_purchase_request) {
            return $this->item($capital_expenditure_request->inventory_purchase_request, new Inventory_purchase_request_transformer);
        }
    }
}
