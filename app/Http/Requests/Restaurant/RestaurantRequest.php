<?php


namespace App\Http\Requests\Restaurant;


use Illuminate\Foundation\Http\FormRequest;

class RestaurantRequest extends FormRequest
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
            'longitude'=>'string|required',
            'latitude'=>'string|required'
        ];
    }
}
