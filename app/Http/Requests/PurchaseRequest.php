<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class PurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = intval($this->compra);
        $rules = [];
        if (empty($id)) {
            $rules =  [
                'status' => ['prohibited', 'in:NOVO,APROVADO,REPROVADO'],
                'amount' => ['prohibited', 'integer'],
            ];
        } else {
            $rules =  [
                'products' => ['prohibited'],
                'status' => ['nullable', 'in:NOVO,APROVADO,REPROVADO'],
                'amount' => ['prohibited', 'integer'],
            ];
        }
        return $rules;
    }
   


    protected function failedValidation(Validator $validator)
    {
        toastr($validator->errors()->first(),'warning');
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
