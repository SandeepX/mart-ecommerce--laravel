<?php


namespace App\Modules\ContentManagement\Repositories;

use App\Modules\ContentManagement\Models\StaticPageImage;


class StaticPageImageRepository
{

    public function getAllSitePagesImageByGroupBy()
    {
        return StaticPageImage::where('is_active',1)->orderBy('created_at','DESC')->groupBy('page_name')->paginate(15);
    }

    public function findorFail($SPICode)
    {
        $data = StaticPageImage::where('static_page_image_code',$SPICode)->firstorFail();
        return $data;
    }

    public function getAllImagesOfStaticPageByPageName($page_name)
    {
        return StaticPageImage::where('page_name',$page_name)->orderBy('updated_at','DESC')->get();
    }

    public function store($validatedData)
    {
        $validatedData['created_by']=  getAuthUserCode();
        return StaticPageImage::create($validatedData)->fresh();
    }

    public function update($staticPageImageData,$validated)
    {
        $validated['updated_by'] = getAuthUserCode();
      return $staticPageImageData->update($validated);
    }

    public function delete($SPICode)
    {

        $staticPageImageData = StaticPageImage::where('static_page_image_code',$SPICode)->first();
        return $staticPageImageData->delete();
    }

}
