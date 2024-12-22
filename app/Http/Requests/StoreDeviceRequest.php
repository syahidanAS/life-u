<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeviceRequest extends FormRequest
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

    public function rules()
    {
        return [
            'user_id' => 'required',
            'token' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'     =>  'Pengguna harus dipilih!',
            'token.required'     =>  'Token harus diisi!',
        ];
    }
}
