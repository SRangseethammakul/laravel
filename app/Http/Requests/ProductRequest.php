<?php

namespace App\Http\Requests;

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
        return [
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'picture' => 'image|mimes:jpeg,jpg,png'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'กรุณาเลือกสินค้า',
            'price.required' => 'กรุณากรอกราคา',
            'price.numeric' => 'กรุณากรอกตัวเลข',
            'category_id.required' => 'กรุณาเลือกประเภทสินค้า',
            'picture.image' => 'กรุณาใส่เฉพาะรูปภาพเท่านั้น',
            'picture.mimes' => 'กรุณาใส่เฉพาะรูปภาพ jpeg jpg png เท่านั้น'
        ];
    }
}

