<?php

namespace App\Modules\Location\Models;

use App\Modules\Application\Traits\ModelCodeGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocationHierarchy extends Model
{
    use ModelCodeGenerator;
    protected $table = 'location_hierarchy';

    protected $primaryKey = 'location_code';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'location_name',
        'slug',
        'location_name_devanagari',
        'upper_location_code',
        'location_code',
        'location_type',
        'headquarter',
        'latitdue',
        'longitude',
        'created_by',
        'updated_by'

    ];

    use Country, Province, District, Municipality, Ward, Tole, SoftDeletes;


    public function generateLocationCode($locationType)
    {
        $location = self::withTrashed()->where('location_type', $locationType)->latest('id')->first();
        if ($locationType == 'district') {
            $modelPrefix='D';
            $initialLocationIndex = 001;
            if ($location) {
                $codeTobePad = str_replace($modelPrefix, "",$location->location_code) + 1;
                $paddedCode = str_pad($codeTobePad, 3, '0', STR_PAD_LEFT);
                $locationCode = $modelPrefix . $paddedCode;
                //$locationCode = ($location->id) + 1;
                //$districtLocationCode = $locationCode;
            }
            else{
                $locationCode = $modelPrefix . $initialLocationIndex;
            }

        } elseif ($locationType == 'municipality') {
            $modelPrefix='M';
            $initialLocationIndex = 0001;
            if ($location) {
                $codeTobePad = str_replace($modelPrefix, "",$location->location_code) + 1;
                $paddedCode = str_pad($codeTobePad, 4, '0', STR_PAD_LEFT);
                $locationCode = $modelPrefix . $paddedCode;
                //$locationCode = ($location->id) + 1;
                //$districtLocationCode = $locationCode;
            }
            else{
                $locationCode = $modelPrefix . $initialLocationIndex;
            }
        } elseif ($locationType == 'ward') {
            $modelPrefix='W';
            $initialLocationIndex = 00001;
            if ($location) {
                $codeTobePad = str_replace($modelPrefix, "",$location->location_code) + 1;
                $paddedCode = str_pad($codeTobePad, 5, '0', STR_PAD_LEFT);
                $locationCode = $modelPrefix . $paddedCode;
                //$locationCode = ($location->id) + 1;
                //$districtLocationCode = $locationCode;
            }
            else{
                $locationCode = $modelPrefix . $initialLocationIndex;
            }
        } else {
            $locationCode = uniqueHash(10);
        }

        return $locationCode;
    }


    public function makeSlug($locationName)
    {
        $initialId = 1;
        $location = self::withTrashed()->latest('id')->first();

        if ($location) {
            $initialId = ($location->id) + 1;
        }

        return $locationName . '-' . $initialId;
    }

    public function generateSlugAndLocationCode($locationName, $locationType)
    {
        $locationCode = $this->generateLocationCode($locationType);
        $slug = $this->makeSlug($locationName);

        return [
            'location_code' => $locationCode,
            'slug' => $slug
        ];

    }

    public function scopeOrderById($query)
    {
        return $query->orderBy('id', 'asc');
    }

    public function lowerLocations()
    {
        return $this->hasMany(self::class, 'upper_location_code');
    }

    // This is method where we implement recursive relationship
    public function nestedLowerLocations()
    {
        return $this->hasMany(self::class, 'upper_location_code')->with('lowerLocations');
    }

    public function upperLocation()
    {
        return $this->belongsTo(self::class, 'upper_location_code');
    }
}
