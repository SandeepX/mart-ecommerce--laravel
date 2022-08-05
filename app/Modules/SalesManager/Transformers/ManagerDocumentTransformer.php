<?php

namespace App\Modules\SalesManager\Transformers;

use App\Modules\SalesManager\Models\ManagerDoc;
use App\Modules\User\Models\UserDoc;


class ManagerDocumentTransformer
{
    private $managerDocs;

    public function __construct($managerDocs)
    {
        $this->managerDocs = $managerDocs;
    }

    public function transform(){
        return $this->handleManagerDocs($this->managerDocs);
    }

    private function handleManagerDocs($managerDocs){

        $managerDocs =$managerDocs->map(function ($managerDoc){
            return [
                'document_name' => $managerDoc->doc_name,
                'document_number' => $managerDoc->doc_number,
                'document_file' =>[photoToUrl($managerDoc->doc,asset(ManagerDoc::DOCUMENT_PATH))]
            ];
        });

       // dd($managerDocs);

        $managerDocs = array_filter($managerDocs->toArray(),function($el){
            return ($el['document_name'] !== NULL && $el['document_name'] !== FALSE && $el['document_name'] !== "");
        });


        $citizenShipFrontIndex = array_search('citizenship-front', array_column($managerDocs, 'document_name'));
        $citizenShipBackIndex = array_search('citizenship-back', array_column($managerDocs, 'document_name'));

        if($citizenShipFrontIndex && $citizenShipBackIndex){
            $citizenFrontImage = $managerDocs[$citizenShipFrontIndex]['document_file'];
            $citizenBackImage = $managerDocs[$citizenShipBackIndex]['document_file'];
            $citizenshipNumber = $managerDocs[$citizenShipFrontIndex]['document_number'];
            unset($managerDocs[$citizenShipFrontIndex],$managerDocs[$citizenShipBackIndex]);

            $managerDocs = array_merge($managerDocs,array([
                "document_name" => "citizenship",
                "document_number" => $citizenshipNumber,
                "document_file" => [
                    $citizenFrontImage[0],
                    $citizenBackImage[0]
                ]
            ]));
        }

        return $managerDocs;
    }
}
