<?php
/**
 * Created by PhpStorm.
 * User: Prajwal
 * Date: 10/8/2020
 * Time: 4:15 PM
 */

namespace App\Modules\Product\Rule;


use Illuminate\Contracts\Validation\Rule;

class PriceMarginRule implements Rule
{
    private $mrp;
    private $margin_type;
    private $customMessage;
    private $hijacked = false;

    public function __construct($mrp,$marginType)
    {
        $this->mrp = $mrp;
        $this->margin_type = $marginType;
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
       // dd($this->margin_type[0]);
        $index = explode('.', $attribute)[1];

        if (!isset($this->margin_type[$index]) || !isset($this->mrp[$index])){

            $this->hijacked =true;
            return false;

        }
        if ($this->margin_type[$index] == 'p'){
            if ($value > 100){
                $this->customMessage ='100';
                return false;
            }
        }
        else {
            if ($value > $this->mrp[$index]) {
                $this->customMessage =$this->mrp[$index];
                return false;
            }
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
        if ($this->hijacked){
            return 'Corrupted data';
        }
        return 'The :attribute must be equal to or less than '.$this->customMessage .'.';
    }

}