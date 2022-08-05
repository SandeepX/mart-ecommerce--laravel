<?php

namespace App\Modules\PromotionLinks\Repositories;

use App\Modules\Application\Abstracts\RepositoryAbstract;
use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\PromotionLinks\Models\PromotionLink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PromotionLinkRepository
{
    use ImageService;

    public function getAllPaginatedPromotionLinks($paginateBy = 10){
        return PromotionLink::orderBy('id','desc')->paginate($paginateBy);
    }

    public function getPromotionLinks(){
        return PromotionLink::orderBy('id','desc')->get();
    }

    public function findPromotionLinkByID($id){
        return PromotionLink::where('id',$id)->first();
    }

    public function findPromotionLinkByLinkCode($linkCode){
        return PromotionLink::where('link_code',$linkCode)->first();
    }

    public function findOrFailPromotionLinkByID($id){
        if($promotionLink = $this->findPromotionLinkByID($id)){
            return $promotionLink;
        }
        throw new ModelNotFoundException('No Such Promotion Link Found !');

    }

    public function findOrFailPromotionLinkByLinkCode($linkCode){
        if($promotionLink = $this->findPromotionLinkByLinkCode($linkCode)){
            return $promotionLink;
        }
        throw new ModelNotFoundException('No Such Promotion Link Found !');

    }

    public function storePromotionLink($validatedData){
        try{

            if(isset($validatedData['og_image'])){
                $validatedData['og_image'] = $this->storeImageInServer($validatedData['og_image'], PromotionLink::OG_IMAGE_PATH);
            }
            if(isset($validatedData['image'])){
                $validatedData['image'] = $this->storeImageInServer($validatedData['image'], PromotionLink::IMAGE_PATH);
            }
            if(isset($validatedData['file'])) {
                $validatedData['file'] = $this->storeImageWithOriginalNameInServer($validatedData['file'], PromotionLink::PROMOTION_FILE_PATH, $validatedData['filename']);
            }
            //$validatedData['link_code'] = $validatedData['link_code'];
            $promotionLink = PromotionLink::create($validatedData);
            return $promotionLink->fresh();
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function update($validated, $promotionLink){
        //store Image

        if(isset($validated['file'])){
            $this->deleteImageFromServer(PromotionLink::PROMOTION_FILE_PATH, $promotionLink->file);
            $validated['file'] = $this->storeImageWithOriginalNameInServer($validated['file'], PromotionLink::PROMOTION_FILE_PATH,make_slug($validated['filename']));
        }
        if(isset($validated['image'])){
            $this->deleteImageFromServer(PromotionLink::IMAGE_PATH, $promotionLink->image);
            $validated['image'] = $this->storeImageInServer($validated['image'], PromotionLink::IMAGE_PATH);
        }
        if(isset($validated['og_image'])){
            $this->deleteImageFromServer(PromotionLink::OG_IMAGE_PATH, $promotionLink->og_image);
            $validated['og_image'] = $this->storeImageInServer($validated['og_image'], PromotionLink::OG_IMAGE_PATH);
        }
        if(($promotionLink->filename !== $validated['filename']) && !isset($validated['file'])){
            $path = public_path(PromotionLink::PROMOTION_FILE_PATH);
            $oldFile = $promotionLink->file;
            if(file_exists($path.$oldFile)){
                $filename = $validated['filename'];
                $fileToReplace = $promotionLink->filename;
                $validated['file'] = str_replace($fileToReplace,$filename,$promotionLink->file);
                $newFile = $validated['file'];
                rename($path.$oldFile,$path.$newFile);
            }
        }
        //$validated['link_code'] = $validated['link_code'];
        $promotionLink->update($validated);
        return $promotionLink->fresh();
    }

    public function delete(PromotionLink $promotionLink) {
        if($promotionLink->file) {
            $this->deleteImageFromServer(PromotionLink::PROMOTION_FILE_PATH, $promotionLink->file);
        }
        if($promotionLink->image) {
            $this->deleteImageFromServer(PromotionLink::IMAGE_PATH, $promotionLink->image);
        }
        if($promotionLink->og_image){
            $this->deleteImageFromServer(PromotionLink::OG_IMAGE_PATH,$promotionLink->og_image);
        }
        $promotionLink->forceDelete();
        return $promotionLink;
    }

}
