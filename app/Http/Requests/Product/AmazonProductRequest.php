<?php


namespace App\Http\Requests\Product;


use Illuminate\Foundation\Http\FormRequest;

class AmazonProductRequest extends FormRequest
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
            'product_id' => 'string|required',
            'asin_type' => 'string|required',
            'asin'=>'string|required|unique:amazon_products,asin,'.$id,
        ];
    }
}
