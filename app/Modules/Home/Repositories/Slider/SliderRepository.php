<?php


namespace App\Modules\Home\Repositories\Slider;

use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Home\Models\Slider;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class SliderRepository
{
    use ImageService;

    private $slider;

    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    public function getAllSliders()
    {
        return $this->slider->latest()->get();
    }

    public function getActiveSliders()
    {
        return $this->slider->active()->latest()->get();
    }

    public function findSliderByCode($sliderCode)
    {
        return $this->slider->where('slider_code', $sliderCode)->first();
    }

    public function findOrFailSliderByCode($sliderCode)
    {
        if (!$slider = $this->findSliderByCode($sliderCode)) {
            throw new ModelNotFoundException('No Such Slider Found !');
        }
        return $slider;
    }

  

    public function createSlider($validated)
    {
        $validated['slider_image'] = $this->storeImageInServer($validated['slider_image'], $this->slider->uploadFolder);
        $validated['is_active'] = 1;
        return $this->slider->create($validated);
    }

    public function updateSlider($validated, $slider)
    {

        if (isset($validated['slider_image'])) {
            $this->deleteImageFromServer($this->slider->uploadFolder, $slider->slider_image);
            $validated['slider_image'] = $this->storeImageInServer($validated['slider_image'],$this->slider->uploadFolder);
        }
        $slider->update($validated);
        return $slider->fresh();
    }

    public function deleteSlider($slider)
    {
        $slider->delete();
        return $slider;
    }



}
