<?php


namespace App\Modules\Vendor\Requests;


use App\Modules\Package\Models\PackageType;
use App\Modules\Product\Models\ProductMaster;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendorProductPackagingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $packageCodes = PackageType::pluck('package_code')->toArray();
       // $microUnitFieldsLength =count($this->request->get('micro_unit_code'));
        //dd($this->all());
        $microUnitFieldsLength =isset($this->micro_unit_code) ? count($this->micro_unit_code): 1;
        $macroUnitFieldsLength =isset($this->macro_unit_code) ? count($this->macro_unit_code): 0;
        $superUnitFieldsLength =isset($this->super_unit_code) ? count($this->super_unit_code): 0;
        //$macroUnitFieldsLength =count($this->request->get('macro_unit_code'));
      //  $superUnitFieldsLength =count($this->request->get('super_unit_code'));
      //  $unitCodeFieldsLength =count($this->request->get('unit_code'));

      //  dd($mrpArrLength,$unit);
        $rules= [
            'micro_unit_code' => ['required','array','min:1'],
            'micro_unit_code.*' => ['required_with:unit_code.*', Rule::in($packageCodes)],

            'unit_code' => ['required','array','size:'.$microUnitFieldsLength,'min:1'],
            'unit_code.*' => ['required_with:micro_unit_code.*', Rule::in($packageCodes)],

            'macro_unit_code' => ['required_with:unit_to_macro_value','array','min:1'],
            'macro_unit_code.*' => ['nullable','required_with:unit_to_macro_value.*',Rule::in($packageCodes)],
            /*'super_unit_code' => 'nullable|array',
            'super_unit_code.*' => ['nullable', Rule::in($packageCodes)],*/

            'super_unit_code' => ['required_with:macro_to_super_value','array','min:1'],
            'super_unit_code.*' => ['nullable','required_with:macro_to_super_value.*',Rule::in($packageCodes)],

            'micro_to_unit_value' => ['required','array','size:'.$microUnitFieldsLength,'min:1'],
           // 'micro_to_unit_value.*' => ['required_with:unit_code.*','regex:/^\d+(\.\d{1,2})?$/'],
            'micro_to_unit_value.*' => ['required_with:unit_code.*','nullable','integer','min:1'],

            'unit_to_macro_value' => ['required_with:macro_unit_code','array','size:'.$macroUnitFieldsLength,'min:1'],
            //'unit_to_macro_value.*' => ['nullable','required_with:macro_unit_code.*','regex:/^\d+(\.\d{1,2})?$/'],
            'unit_to_macro_value.*' => ['nullable','required_with:macro_unit_code.*','nullable','integer','min:1'],

            'macro_to_super_value' => ['required_with:super_unit_code','array','size:'.$superUnitFieldsLength,'min:1'],
            //'macro_to_super_value.*' => ['nullable','required_with:super_unit_code.*','regex:/^\d+(\.\d{1,2})?$/'],
            'macro_to_super_value.*' => ['nullable','required_with:super_unit_code.*','nullable','integer','min:1'],
        ];

        $product = ProductMaster::find($this->route('product_code'));

        if ($product){
            $variantCodes = $product->productVariants()->pluck('product_variant_code')->toArray();
        }
        if(isset($variantCodes)){
            $rules['product_variant_code.*'] = ['distinct',Rule::in($variantCodes)];
        }else{
            $rules['product_variant_code.*'] =[Rule::in(null)];
        }

        return $rules;
    }

    public function messages()
    {
        return [
           // 'micro_unit_code.size' => 'Number of micro unit code and unit code should be equal.',
            'unit_code.size' => 'Number of micro unit code and unit code should be equal.',
            'micro_to_unit_value.size' => 'Number of micro unit code and micro to unit value should be equal.',

        ];
    }
}
