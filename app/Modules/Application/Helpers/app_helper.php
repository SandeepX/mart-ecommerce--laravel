<?php

use Carbon\CarbonTimeZone;
use Illuminate\Support\Carbon;


if (!function_exists('make_slug')) {
    function make_slug($string)
    {
        return \Illuminate\Support\Str::slug($string);
    }
}

if (!function_exists('makeSlugWithHash')) {
    function makeSlugWithHash($string)
    {
        return make_slug($string) . '-' . uniqueHash(8);
    }
}

if (!function_exists('formatWords')) {
    function formatWords($sentence, $plural = false)
    {
        return ($plural == false) ? ucwords(str_replace('-', ' ', \Illuminate\Support\Str::singular($sentence))) : (ucwords(str_replace('-', ' ', \Illuminate\Support\Str::plural($sentence))));
    }
}

if (!function_exists('stringLimit')) {
    function stringLimit($sentence, $length = 50)
    {
        return Str::limit($sentence, $length);
    }
}

if (!function_exists('uniqueHash')) {

    function uniqueHash($limit = 10, $extra = null)
    {
        return substr(base_convert(sha1($extra . time() . date('Y-m-d') . uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}

if (!function_exists('photoToUrl')) {

    function photoToUrl($image, $directory_path)
    {
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $photo = $image;
        } else {
            $photo = $image ?  $directory_path . '/' . $image : '';
        }
        return $photo;
    }
}

if (!function_exists('convertToArray')) {

    function convertToArray($variable)
    {
        if (is_array($variable)) {
            return $variable;
        }
        return array($variable);
    }
}

if (!function_exists('implodeArray')) {

    function implodeArray($variable)
    {
        $variable = convertToArray($variable);
        array_walk($variable, function (&$value, $key) {
            $value = '"' . $value . '"';
        });
        return (implode(",", $variable));
    }
}

if (!function_exists('sendSuccessResponse')) {

    function sendSuccessResponse($message, $data = null,$headers=[],$options=0)
    {
        $response = [
            'error'   => false,
            'message' => $message,
            'code'    => 200
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200,$headers,$options);
    }
}

if (!function_exists('successResponseWithoutDataWrapping')) {

    function successResponseWithoutDataWrapping($message, $data)
    {

        $response = [
            'error'   => false,
            'message' => $message,
            'code'    => 200,
            'data' => $data
        ];

        return response()->json($response, 200);
    }
}

if (!function_exists('sendErrorResponse')) {

    function sendErrorResponse($message, $code = 500, array $errorFields = null)
    {

        $response = [
            'error'   => true,
            'message' => $message,
            'code'    => $code,
        ];

        if (!is_null($errorFields)) {
            $response['data'] = $errorFields;
        }

        if ($code < 200 || !is_numeric($code) || $code > 599) {
            $code = 500;
            $response['code'] = $code;
        }
        return response()->json($response, $code);
    }
}

if (!function_exists('slugToWords')) {

    function slugToWords($slug)
    {

        return ucwords(str_replace("-", " ", $slug));
    }
}

if (!function_exists('convertToWords')) {

    function convertToWords($toBeReplaced, $slug = "-")
    {
        return ucwords(str_replace($slug, " ", $toBeReplaced));
    }
}

if (!function_exists('hasImageExtension')) {

    function hasImageExtension($fileName)
    {
        $imageExtensions = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg', 'svgz',
            'cgm', 'djv', 'djvu', 'ico', 'ief','jpe', 'pbm', 'pgm', 'pnm', 'ppm',
            'ras', 'rgb', 'tif', 'tiff', 'wbmp', 'xbm', 'xpm', 'xwd'
        ];

        $explodeImage = explode('.', $fileName);
        $extension = end($explodeImage);

        if(in_array($extension, $imageExtensions))
        {
            // Is image
            return true;
        }
        return false;
    }
}

if (!function_exists('areArraysValueEqual')) {

    function areArraysValueEqual(array  $array1, array $array2)
    {

        if (count($array1) != count($array2)) {
            throw new Exception('Arrays length not equal');
        }

        foreach ($array1 as $item) {
            if (!in_array($item, $array2)) {
                return false;
            }
        }

        return true;
    }
}

if (!function_exists('roundPrice')) {

    function roundPrice($price)
    {
        return round($price, 2);
    }
}

if (!function_exists('formatProductPriceRange')) {

    function formatProductPriceRange(array $prices)
    {

        if (count($prices) == 1) {
            return 'Rs.' . roundPrice($prices[0]);
        } else {
            return 'Rs.' . roundPrice($prices[0]) . '- Rs.' . roundPrice($prices[1]);
        }
    }
}

if (!function_exists('validateDate')) {
    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
}

if (!function_exists('convertEnglishNumToNepali')) {
    function convertEnglishNumToNepali($number)
    {
        $eng_number = array(
            0,
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9
        );
        $nep_number = array(
            '०',
            '१',
            '२',
            '३',
            '४',
            '५',
            '६',
            '७',
            '८',
            '९'
        );
        return str_replace($eng_number, $nep_number, $number);

    }
}

if (!function_exists('getNepTimeZoneDateTime')) {
    function getNepTimeZoneDateTime($value)
    {

        $date = new Carbon($value);
        $date->setTimezone(new CarbonTimeZone('Asia/Kathmandu'));
        return $date->format('Y-m-d H:i:s');
    }
}

function currencyInWords($number) {
    $no = $number;
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );

    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore','Arab','Kharab','Neel');
    $diff = $decimal - $decimal%10;
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            if($diff == 100){
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[($number % 10) + 1] . ' ' . $digits[$counter] . $plural;
            }else{
                $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
            }

        } else {
            $str [] = null;
        }
    }

    $Rupees = implode(' ', array_reverse($str));
     $diff = $decimal - $decimal%10;
    if($diff == 10){
        $sum = $diff +  $decimal%10;
        $paise = ($decimal) ? " And " . ($words[$sum]) . ' Paisa' : '';
    }else{
        if($diff != 100){
            $paise = ($decimal) ? " And " . ($words[$decimal - $decimal%10]) ." " .(($decimal%10) == 0 ? '' :($words[((string)($decimal) -(intdiv((string)($decimal),10) * 10))].' ')). 'Paisa' : '';
        }else{
            $paise = null;
        }
    }

   // dd($paise);

    $rupia = ($Rupees ? $Rupees .'Rupees' : '');

    $output= (!is_null($paise)) ? $rupia.$paise.' Only' : $rupia."Only";
    return preg_replace('!\s+!', ' ', $output);
}

if (!function_exists('manageProductRatingValue')) {
    function manageProductRatingValue($value)
    {

        switch ($value){
            case ($value > 1 && $value < 1.5):
                $rating =  1.5;
                return $rating;

            case ($value > 1.5 && $value < 2):
                $rating =  2;
                return $rating;

            case ($value > 2 && $value < 2.5):
                $rating =  2.5;
                return $rating;

            case ($value > 2.5 && $value < 3):
                $rating =  3;
                return $rating;

            case ($value > 3 && $value < 3.5):
                $rating =  3.5;
                return $rating;

            case ($value > 3.5 && $value < 4):
                $rating =  4;
                return $rating;

            case ($value > 4 && $value < 4.5):
                $rating =  4.5;
                return $rating;

            case ($value > 4.5 && $value < 5):
                $rating =  5;
                return $rating;


                return $rating;

        }
    }

}

if (!function_exists('customArrayPaginator')) {
    function customArrayPaginator($array,$request, $perPage = 10)
    {
        $page = $request->get('page', 1);
        $offset = ($page * $perPage) - $perPage;
        return new \Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}

if (!function_exists('getReadableDate')) {
    function getReadableDate($date,$dateFormat='Y-M-d h:i:s A')
    {
        return date($dateFormat,strtotime($date));
    }
}
if (!function_exists('getDefaultImage')) {
    function getDefaultImage()
    {
        return url('default/images/alplogo.png');
    }
}

 function getNumberFormattedAmount($amount,$precision=2)
{
    return number_format($amount,$precision);
}

if(!function_exists('diffDate')){
    function diffDate($from , $to=null){

        $datetime1 =  ($to) ? $to : Carbon::now();
        $datetime2 =  $from;
        $interval = $datetime1->diff($datetime2);
        $elapsed_for = $interval->format(' %a days %h hours %i mins');
        return $elapsed_for;

    }
}
if(!function_exists('array_group_by')){


    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    function array_group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }
}

if(!function_exists('convertRsToPaisa')){
    function convertRsToPaisa($rsAmount){
        if ($rsAmount <= 0){
            throw new Exception('Amount should be greater than 0.');
        }

        return $rsAmount*100;
    }
}


if(!function_exists('convertPaisaToRs')){
    function convertPaisaToRs($paisaAmount){

        if ($paisaAmount <= 0){
            throw new Exception('Amount should be greater than 0.');
        }

        return $paisaAmount/100;
    }
}

if(!function_exists('checkIfFileExists')){
    function checkIfFileExists($image, $directory_path,$driver = 'public'){
        $fileSystemDrivers = array_keys(config('filesystems.disks'));
        if(!in_array($driver,$fileSystemDrivers)){
            return  false;
        }
        if($driver == 'public'){
            return file_exists(public_path($directory_path.'/'.$image));
        }else{
           return  \Illuminate\Support\Facades\Storage::disk($driver)->exists($directory_path.'/'.$image);
        }
    }
}

if(!function_exists('getHolderId')){
    function getHolderId($namespace){
        return $namespace::where('user_code',getAuthUserCode())->first()->getKey();
    }
}

if(!function_exists('removeSpecialChar')){
    function removeSpecialChar($str){
        $text = preg_replace('/\\s+/', ' ', $str);
        $text = preg_replace("/\r|\n/", "", $text);
        $text = strip_tags($text);
        // $res = preg_replace('/[^A-Za-z0-9\-][0-9\@\.\;\" "]+/', ' ', $text);
        return $text;
    }
}
if(!function_exists('truncateNumberAfterDecimal')){
     function truncateNumberAfterDecimal($value, $precision) {
        $multiplier = pow(10, $precision);
        $value = (int)($value * $multiplier);
        $x = $value / $multiplier;
        return bcadd($x,0,$precision);
    }
}

if(!function_exists('getDistanceInKm')){
    function getDistanceInKm($latitude1, $longitude1, $latitude2, $longitude2) {
        $earth_radius = 6371;
        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;
        return $d;
    }
}












