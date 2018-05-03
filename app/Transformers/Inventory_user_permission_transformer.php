<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Transformers\User_transformer;

class Inventory_user_permission_transformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    protected $availableIncludes = ['user'];
    public function transform($user_permission)
    {
        return [
            'id' => $user_permission->id,
            'user_id' => $user_permission->user_id,
            'can_view_items' => $user_permission->can_view_items == 1,
            'can_add_items' => $user_permission->can_add_items == 1,
            'can_edit_items' => $user_permission->can_edit_items == 1,
            'can_delete_items' => $user_permission->can_delete_items == 1,
            'can_view_purchase_requests' => $user_permission->can_view_purchase_requests == 1,
            'can_add_purchase_requests' => $user_permission->can_add_purchase_requests == 1,
            'can_edit_purchase_requests' => $user_permission->can_edit_purchase_requests == 1,
            'can_delete_purchase_requests' => $user_permission->can_delete_purchase_requests == 1,
            'can_approve_purchase_requests' => $user_permission->can_approve_purchase_requests == 1,
            'can_view_request_to_canvasses' => $user_permission->can_view_request_to_canvasses == 1,
            'can_add_request_to_canvasses' => $user_permission->can_add_request_to_canvasses == 1,
            'can_edit_request_to_canvasses' => $user_permission->can_edit_request_to_canvasses == 1,
            'can_delete_request_to_canvasses' => $user_permission->can_delete_request_to_canvasses == 1,
            'can_view_capital_expenditure_requests' => $user_permission->can_view_capital_expenditure_requests == 1,
            'can_add_capital_expenditure_requests' => $user_permission->can_add_capital_expenditure_requests == 1,
            'can_edit_capital_expenditure_requests' => $user_permission->can_edit_capital_expenditure_requests == 1,
            'can_delete_capital_expenditure_requests' => $user_permission->can_delete_capital_expenditure_requests == 1,
            'can_approve_capital_expenditure_requests' => $user_permission->can_approve_capital_expenditure_requests == 1,
            'can_view_purchase_orders' => $user_permission->can_view_purchase_orders == 1,
            'can_add_purchase_orders' => $user_permission->can_add_purchase_orders == 1,
            'can_edit_purchase_orders' => $user_permission->can_edit_purchase_orders == 1,
            'can_delete_purchase_orders' => $user_permission->can_delete_purchase_orders == 1,
            'can_approve_purchase_orders' => $user_permission->can_approve_purchase_orders == 1,
            'can_view_receiving_reports' => $user_permission->can_view_receiving_reports == 1,
            'can_add_receiving_reports' => $user_permission->can_add_receiving_reports == 1,
            'can_edit_receiving_reports' => $user_permission->can_edit_receiving_reports == 1,
            'can_delete_receiving_reports' => $user_permission->can_delete_receiving_reports == 1,
            'can_view_stock_issuances' => $user_permission->can_view_stock_issuances == 1,
            'can_add_stock_issuances' => $user_permission->can_add_stock_issuances == 1,
            'can_edit_stock_issuances' => $user_permission->can_edit_stock_issuances == 1,
            'can_delete_stock_issuances' => $user_permission->can_delete_stock_issuances == 1,
            'can_approve_stock_issuances' => $user_permission->can_approve_stock_issuances == 1,
        ];
    }

    public function includeUser ($user_permission)
    {
        if($user_permission->user) {
            return $this->item($user_permission->user, new User_transformer);
        }
    }
}
