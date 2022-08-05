<?php
namespace App\Modules\Application\Rules;
use Illuminate\Contracts\Validation\Rule;

class ValidateFileExtension implements Rule
{
    private $allExtension;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($allExtension)
    {
        $this->allExtension = $allExtension;
    }
    public function checkFileExtensions($fileExtension){
        if(in_array($fileExtension,$this->allExtension)){
            return true;
        }
        return false;
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
        $extension = $value->getClientOriginalExtension();
        if($extension){
            return $this->checkFileExtensions($extension);
        }
        return false;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The File Extension is Not Supported. Supported Extension are '.implode(' | ',$this->allExtension).'.';
    }
}
