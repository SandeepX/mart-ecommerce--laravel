<?php

namespace App\Modules\Home\Services\Slider;

use App\Modules\Home\Repositories\Slider\SliderRepository;
use Exception;

class SliderService
{
    private $sliderRepo;

    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepo = $sliderRepository;
    }

    public function getAllSliders()
    {
        return $this->sliderRepo->getAllSliders();
    }

    public function getActiveSliders()
    {
        return $this->sliderRepo->getActiveSliders();
    }

  
    public function findSliderByCode($sliderCode)
    {
        return $this->sliderRepo->findSliderByCode($sliderCode);
    }

   

    public function findOrFailSliderByCode($sliderCode)
    {
      return $this->sliderRepo->findOrFailSliderByCode($sliderCode);
    }

  

    public function storeSlider($validated){
        try{
            $slider = $this->sliderRepo->createSlider($validated);
            return $slider;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateSlider($validated,$sliderCode){
        try{
            $slider = $this->findOrFailSliderByCode($sliderCode);
            $slider = $this->sliderRepo->updateSlider($validated,$slider);
            return $slider;    
        }catch(Exception $exception){
            throw $exception;

        }
    }

    public function deleteSlider($sliderCode){
        try{
            $slider = $this->sliderRepo->findOrFailSliderByCode($sliderCode);
            return $this->sliderRepo->deleteSlider($slider);
        }catch(Exception $exception){
            throw $exception;
        }
    }

}