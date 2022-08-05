<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/5/2020
 * Time: 4:38 PM
 */

namespace App\Modules\RolePermission\Requests;


use App\Modules\RolePermission\Services\RoleService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleStoreRequest extends FormRequest
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
     * Prepare the data for validation.
     * sanitize any data from the request before you apply your validation rules
     * @return void
     */
    protected function prepareForValidation()
    {
        $trimmedName = preg_replace('/\s+/', ' ', $this->name);
        $this->merge([
            'name' => $trimmedName,
            'slug' => make_slug($trimmedName),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $roleUserTypes = RoleService::getRoleUserTypes();
        return [
            'name' => ['bail','required','max:100',Rule::unique('roles', 'name')],
            'slug' => ['bail','required_with:name','max:190',Rule::unique('roles', 'slug')],
            'for_user_type' => ['bail','required',Rule::in($roleUserTypes)],
            'description' => ['required','max:50000'],
            'permission_id'=>['required','array'],
            'permission_id.*' => ['required','distinct',Rule::exists('permissions', 'id')]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'Given role name already exists',
            'slug.unique' => 'Given role name already exists, try maintaining spaces',
            'permission_id.required' => 'At least one permission required',
            'permission_id.*.required' => 'At least one permission required',
            'permission_id.*.distinct' => 'Invalid permission',
            'permission_id.*.exists' => 'Invalid permission',
        ];
    }
}