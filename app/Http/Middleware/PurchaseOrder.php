<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Inventory\Inventory_user_permission;

class PurchaseOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $allowed_permission)
    {
        if(Auth::user()->privilege=="admin"){
            return $next($request);
        }else{
            $permissions = Inventory_user_permission::where('user_id',Auth::user()->id)->first();
            switch ($allowed_permission) {
                case 'view':
                    if($permissions->can_view_purchase_orders == 1){
                        return $next($request);
                    }
                    break;
                case 'add':
                    if($permissions->can_add_purchase_orders == 1){
                        return $next($request);
                    }
                    break;
                case 'edit':
                    if($permissions->can_edit_purchase_orders == 1){
                        return $next($request);
                    }
                    break;
                case 'delete':
                    if($permissions->can_delete_purchase_orders == 1){
                        return $next($request);
                    }
                    break;
                case 'approve':
                    if($permissions->can_approve_purchase_orders == 1){
                        return $next($request);
                    }
                    break;
                    
                default:
                    break;
            }
        }
        return abort(403);
    }
}
