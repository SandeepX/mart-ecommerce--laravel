<?php


namespace App\Modules\B2cCustomer\Transformers;

use App\Modules\Location\Repositories\LocationHierarchyRepository;
use App\Modules\User\Models\UserDoc;

class B2CUserDocumentTransformer
{
    private $userDocs;

    public function __construct($userDocs)
    {
        $this->userDocs = $userDocs;
    }

    public function transform()
    {
        return $this->handleManagerDocs($this->userDocs);
    }

    private function handleManagerDocs($userDocs)
    {

        $userDocs = $userDocs->map(function ($userDocs) {
            return [
                'document_name' => str_replace('_',' ',$userDocs->doc_name),
                'document_slug' => $userDocs->doc_name,
                'document_number' => $userDocs->doc_number,
                'doc_issued_district' => $userDocs->doc_issued_district,
                'document_file' => photoToUrl($userDocs->doc, asset(UserDoc::DOCUMENT_PATH))
            ];
        });

        $userDocs = $userDocs->toArray();

        $citizenShipFrontIndex = array_search('citizenship-front', array_column($userDocs, 'document_name'));
        $citizenShipBackIndex = array_search('citizenship-back', array_column($userDocs, 'document_name'));

        if($citizenShipFrontIndex){
            $citizenFrontImage = $userDocs[$citizenShipFrontIndex]['document_file'];
            $citizenBackImage = $userDocs[$citizenShipBackIndex]['document_file'];
            $citizenshipNumber = $userDocs[$citizenShipFrontIndex]['document_number'];
            $issuedDistrict = (new LocationHierarchyRepository)->getLocationByCode($userDocs[$citizenShipFrontIndex]['doc_issued_district']);
            $districtName = $issuedDistrict['location_name'];
            unset($userDocs[$citizenShipFrontIndex], $userDocs[$citizenShipBackIndex]);

            $userDocs = array_merge($userDocs, array([
                "document_name" => "citizenship",
                "document_slug" => "citizenship",
                "document_number" => $citizenshipNumber,
                "issued_district" => $districtName,
                "document_file" => [
                    $citizenFrontImage,
                    $citizenBackImage
                ]
            ]));

        }



        return $userDocs;
    }
}

