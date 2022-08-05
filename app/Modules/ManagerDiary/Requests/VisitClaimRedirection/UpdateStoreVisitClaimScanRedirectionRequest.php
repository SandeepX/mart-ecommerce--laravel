<?php

namespace App\Modules\ManagerDiary\Requests\VisitClaimRedirection;

use App\Modules\Application\Rules\ValidateFileExtension;
use App\Modules\ManagerDiary\Models\VisitClaim\StoreVisitClaimScanRedirection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStoreVisitClaimScanRedirectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'=>['required','string'],
            'image' => ['nullable',
                'image',
                new ValidateFileExtension(["jpeg","png","jpg","webp"]),
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
            'app_page' => ['bail','nullable',
                                'required_without:external_link',
                Rule::unique('store_visit_claim_scan_redirections')
                    ->ignore($this->route('visit_claim_scan_redirection'),'store_visit_claim_scan_redirection_code')
                    ->whereNotNull('app_page'),
                Rule::in(StoreVisitClaimScanRedirection::APP_PAGE)
            ],
            'external_link' => ['bail','nullable','url','required_without:app_page'],
            'is_active' => ['required',Rule::in(0,1)]
        ];
    }

}
