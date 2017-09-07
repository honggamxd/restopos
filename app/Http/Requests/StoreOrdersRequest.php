<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreOrdersRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'table_customer_cart' => 'required|available_to_cook',
            'body' => 'required',
        ];
    }

    public function store(StoreOrdersRequest $request)
    {
        $validator = \Validator::make($request->all(), $this->rules());

        if (!$validator->fails()) {
           // return response()->json($validator->errors(), 422);
        }
    }
}
