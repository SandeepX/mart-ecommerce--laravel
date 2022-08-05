<?php


namespace App\Modules\Location\Requests\BlacklistedLocation;

use App\Modules\Location\Models\LocationBlacklisted;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BlacklistedLocationRequest extends FormRequest
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
            'location_code' => 'required|string|exists:location_hierarchy,location_code',
            'purpose' => ['required', Rule::in(LocationBlacklisted::PURPOSE)],
            'status' => ['required','boolean']
        ];
    }

}

