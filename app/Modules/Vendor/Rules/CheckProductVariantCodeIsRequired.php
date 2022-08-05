<?php


namespace App\Modules\Vendor\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckProductVariantCodeIsRequired implements Rule
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

        $indexes =  explode('.', $attribute);
        $index = $indexes[1];
        $secondIndex =  $indexes[3];

        $combination_status = request()->input("variant_groups.{$index}.combinations.{$secondIndex}.combination_status");

        if($combination_status==='old_data' && !$value){
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
        return 'The :attribute is required if it is old data.';
    }


}
