<?php

namespace App\Modules\PromotionLinks\Controllers\Api\Front;

use App\Modules\PromotionLinks\Models\PromotionLink;
use App\Modules\PromotionLinks\Resources\PromotionLinksCollection;
use App\Modules\PromotionLinks\Resources\SinglePromotionLinkResource;
use App\Modules\PromotionLinks\Services\PromotionLinkService;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;


class PromotionLinksApiController
{
    private $promotionLinkService;

    public function __construct(PromotionLinkService $promotionLinkService)
    {
        $this->promotionLinkService = $promotionLinkService;
    }

    public function getAllPromotionLinks()
    {
        try {
            $promotionLinks = $this->promotionLinkService->getAllPromotionLinks();
            return new PromotionLinksCollection($promotionLinks);
        } catch (Exception $exception) {
            return sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getPromotionLinkDetail($linkCode)
    {
        try {
            $link = $this->promotionLinkService->findOrFailPromotionLinkByLinkCode($linkCode);
            $promotionLink = new SinglePromotionLinkResource($link);
            return sendSuccessResponse('Data Found!', $promotionLink);
        } catch (Exception $exception) {
            return sendErrorResponse('Sorry !');
        }
    }

    public function downloadPromotionLink($linKcode)
    {
        $link = $this->promotionLinkService->findOrFailPromotionLinkByLinkCode($linKcode);
        $filePath = public_path(PromotionLink::PROMOTION_FILE_PATH) . $link->file;
        $fileName = $link->file;
        return  new StreamedResponse(
            function() use ($filePath, $fileName) {
                // Open output stream
                if ($file = fopen($filePath, 'rb')) {
                    while(!feof($file) and (connection_status()==0)) {
                        print(fread($file, 1024*8));
                        ob_flush();
                        flush();
                    }
                    fclose($file);
                }
            },
            200,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
    }
}
