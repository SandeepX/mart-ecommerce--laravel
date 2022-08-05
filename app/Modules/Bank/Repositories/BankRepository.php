<?php


namespace App\Modules\Bank\Repositories;



use App\Modules\Application\Traits\UploadImage\ImageService;
use App\Modules\Bank\Models\Bank;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class BankRepository
{

  use ImageService;

  public function getAllBanks(){
    return Bank::latest()->get();
  }

  public function findBankById($bankId){
    return Bank::where('id',$bankId)->first();
  }

   public function findOrFailBankById($bankId){
       if($bank = $this->findBankById($bankId)){
         return $bank;
       }

       throw new ModelNotFoundException('No Such Bank Found !');

   }

  public function findBankByCode($bankCode){
        return Bank::where('bank_code',$bankCode)->first();
  }

  public function findOrFailBankByCode($bankCode){
      if($bank = $this->findBankByCode($bankCode)){
          return $bank;
      }
      throw new ModelNotFoundException('No Such Bank Found !');

  }

  public function findBankBySlug($bankSlug){
        return Bank::where('slug',$bankSlug)->first();
  }

  public function findOrFailBankBySlug($bankSlug){
      if($bank = $this->findBankBySlug($bankSlug)){
          return $bank;
      }
      throw new ModelNotFoundException('No Such Bank Found !');

  }

  public function create($validated){
    //store Image
    $validated['bank_logo'] = $this->storeImageInServer($validated['bank_logo'], 'uploads/banks');
    $validated['slug'] = make_slug($validated['bank_name']);
    return Bank::create($validated)->fresh();

  }

  public function update($validated, $bank){
    //store Image
    if(isset($validated['bank_logo'])){
      $this->deleteImageFromServer('uploads/banks', $bank->Bank_logo);
      $validated['bank_logo'] = $this->storeImageInServer($validated['bank_logo'], 'uploads/banks');
    }

    $validated['slug'] = make_slug($validated['bank_name']);
    $bank->update($validated);
    return $bank->fresh();

  }

  public function delete($bank) {
     $bank->delete();
     return $bank;
  }



}
