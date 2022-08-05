<?php

namespace App\Modules\Vendor\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckGroupCodeIsRequired implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $index = explode('.', $attribute)[1];
        $group_status = request()->input("variant_groups.{$index}.group_status");

        if($group_status==='old_data' && !$value){
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is required for old data.';
    }
}
