<?php

namespace App\Http\Requests\Mission;

use Illuminate\Foundation\Http\FormRequest;

class OrderTargetRequest extends FormRequest
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
        $id = null;

        if($this->id)
        {
            $id = $this->id;
        }
        return [
            'title'=>'string|required',
            'month'=>'numeric|required|unique:order_targets,month,'.$id,
            'order_count'=>'numeric|required',
            'order_total'=>'numeric|required'
        ];
    }

}
