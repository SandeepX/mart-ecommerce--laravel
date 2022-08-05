<?php


namespace App\Modules\SupportAdmin\Requests;

use App\Modules\GlobalNotification\Models\GlobalNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupportAdminSearchStoreRequest extends FormRequest
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
            'store_code' => 'nullable|string|max:1000',
            'store_name' => 'nullable|string|max:1000',
            'store_email' => 'nullable|email|max:1000',
            'store_phone' => 'nullable|numeric'

        ];
    }

}
