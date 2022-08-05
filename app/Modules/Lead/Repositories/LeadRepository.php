<?php

namespace App\Modules\Lead\Repositories;


use App\Modules\Lead\Models\Lead;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class LeadRepository
{


    public function getAllLeads(){
       return Lead::withCount('documents')->latest()->get();
    }




    public function findLeadByCode($leadCode){
        return Lead::where('lead_code', $leadCode)->first();
    }

    public function findOrFailLeadByCode($leadCode){
        if($lead = $this->findLeadByCode($leadCode)){
          return $lead;
        }
 
        throw new ModelNotFoundException('No Such Lead Found !');
 
    }

    public function findLeadById($leadId){
        return Lead::where('id',$leadId)->first();
    }

    public function findOrFailLeadById($leadId){
        if($lead = $this->findLeadById($leadId)){
          return $lead;
        }
 
        throw new ModelNotFoundException('No Such Lead Found !');
 
    }

    public function findLeadBySlug($leadSlug){
        return Lead::where('slug',$leadSlug)->first();
    }

    public function findOrFailLeadBySlug($leadSlug){
        if($lead = $this->findLeadBySlug($leadSlug)){
          return $lead;
        }
 
        throw new ModelNotFoundException('No Such Lead Found !');
 
    }

   

    public function create($validated){
        return Lead::create($validated)->fresh();
    }

    public function update($validated, $lead){
        $lead->update($validated);
        return $lead->fresh();
    }

    public function delete($lead) {
        $lead->delete(); //trash lead
        $lead->documents()->delete(); // trash lead documents
        return $lead;
    }





}
