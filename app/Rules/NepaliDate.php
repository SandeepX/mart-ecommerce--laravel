<?php

namespace App\Rules;

use App\Modules\Application\Traits\NepaliCalendar;
use DateTime;
use Illuminate\Contracts\Validation\Rule;

class NepaliDate implements Rule
{
    use NepaliCalendar;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    /**
     * Determine if the validation rule passes.createFromFormat
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return true;
        // incoming $value is nepali date of format y-m-d (2077-07-23)
        
        $explodedNepDate = explode('-', $value);
        if (count($explodedNepDate) !== 3) {
            return false;
        }
        $nepYear = $explodedNepDate[0];
        $nepMonth = $explodedNepDate[1];
        $nepDay = $explodedNepDate[2];

        $englishDateFromNepali = $this->nep_to_eng($nepYear, $nepMonth, $nepDay);
        
        $engDate = $englishDateFromNepali['y'].'-'.$englishDateFromNepali['m'].'-'.$englishDateFromNepali['d'];

        return $this->validateDate($engDate) ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  ':attribute should be valid nepali date';
    }
}
