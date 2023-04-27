<?php


namespace App\Http\Requests\Product;


use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name'=>'string|required',
//            'upc'=>'string|required|unique:products,upc,'.$id,
            'sku'=>'string|required',
            'image'=>'nullable|mimes:png,jpeg,jpg',
        ];
    }
}
