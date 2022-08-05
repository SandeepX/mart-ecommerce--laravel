<?php

namespace App\Modules\RolePermission\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //it is best to flush this package’s cache before seeding, to avoid cache conflict errors.
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $defaultGuard = 'web';
        //permission_name => verb_noun;


        $arrayOfPermissions =[

            //roles permissions
            [
                'name' => 'Create Role',
                'category' => 'Role',
                'description' => 'can add role',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Role List',
                'category' => 'Role',
                'description' => 'can view role lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Role',
                'category' => 'Role',
                'description' => 'can view single role',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Role',
                'category' => 'Role',
                'description' => 'can update role',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Role',
                'category' => 'Role',
                'description' => 'can delete role',
                'permission_for' => 'admin',

            ],
            //Admin permissions
            [
                'name' => 'Create Admin',
                'category' => 'Admin',
                'description' => 'can add admin',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Admin List',
                'category' => 'Admin',
                'description' => 'can view Admin lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Admin',
                'category' => 'Admin',
                'description' => 'can view single Admin detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Admin',
                'category' => 'Admin',
                'description' => 'can update Admin',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Admin',
                'category' => 'Admin',
                'description' => 'can delete Admin',
                'permission_for' => 'admin',

            ],
            //Vendor permissions
            [
                'name' => 'Create Vendor',
                'category' => 'Vendor',
                'description' => 'can add Vendor',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Vendor List',
                'category' => 'Vendor',
                'description' => 'can view Vendor lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Vendor',
                'category' => 'Vendor',
                'description' => 'can view single Vendor detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Vendor',
                'category' => 'Vendor',
                'description' => 'can update Vendor',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Vendor',
                'category' => 'Vendor',
                'description' => 'can delete Vendor',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Vendor Status',
                'category' => 'Vendor',
                'description' => 'can update Vendor status',
                'permission_for' => 'admin',
            ],

            [
                'name' => 'Create Vendor Document',
                'category' => 'Vendor',
                'description' => 'can add Vendor Document',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Vendor Document List',
                'category' => 'Vendor',
                'description' => 'can view Vendor Document lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Vendor Document',
                'category' => 'Vendor',
                'description' => 'can delete Vendor document',
                'permission_for' => 'admin',
            ],

            //Vendor Admin permissions
            [
                'name' => 'Create Vendor Admin',
                'category' => 'Vendor Admin',
                'description' => 'can add Vendor admin',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Vendor Admin List',
                'category' => 'Vendor Admin',
                'description' => 'can view Vendor Admin lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Vendor Admin',
                'category' => 'Vendor Admin',
                'description' => 'can view single Vendor Admin detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Vendor Admin',
                'category' => 'Vendor Admin',
                'description' => 'can update Vendor Admin',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Vendor Admin',
                'category' => 'Vendor Admin',
                'description' => 'can delete Vendor Admin',
                'permission_for' => 'admin',

            ],

            //Store permissions
            [
                'name' => 'Create Store',
                'category' => 'Store',
                'description' => 'can add Store',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Store List',
                'category' => 'Store',
                'description' => 'can view Store lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store',
                'category' => 'Store',
                'description' => 'can view single Store detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Store',
                'category' => 'Store',
                'description' => 'can update Store',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Store',
                'category' => 'Store',
                'description' => 'can delete Store',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Store Status',
                'category' => 'Store',
                'description' => 'can update Store status',
                'permission_for' => 'admin',
            ],

            [
                'name' => 'Create Store Document',
                'category' => 'Store',
                'description' => 'can add Store Document',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Store Document List',
                'category' => 'Store',
                'description' => 'can view Store Document lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Store Document',
                'category' => 'Store',
                'description' => 'can delete Store document',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Store Purchase Power',
                'category' => 'Store',
                'description' => 'can update Store Purchase Power',
                'permission_for' => 'admin',
            ],
            //store warehouse permissions
            [
                'name' => 'Create Store Warehouse',
                'category' => 'Store Warehouse',
                'description' => 'can add Store Warehouse',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'View Store Warehouse List',
                'category' => 'Store Warehouse',
                'description' => 'can view Store Warehouse list',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Show Store Warehouse',
                'category' => 'Store Warehouse',
                'description' => 'can view single Store Warehouse detail',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Update Store Warehouse',
                'category' => 'Store Warehouse',
                'description' => 'can update Store Warehouse',
                'permission_for' => 'warehouse',

            ],

            //store order permissions
            [
                'name' => 'View Store Order List',
                'category' => 'Store Order',
                'description' => 'can view Store Order list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Order',
                'category' => 'Store Order',
                'description' => 'can view single Store Order detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Store Order',
                'category' => 'Store Order',
                'description' => 'can verify Store Order',
                'permission_for' => 'admin',

            ],
//            [
//                'name' => 'Verify Store Order',
//                'category' => 'Store Order',
//                'description' => 'can verify Store Order',
//
//            ],
            [
                'name' => 'Change Status Of Store Order',
                'category' => 'Store Order',
                'description' => 'can Change Status Of Store Order',
                'permission_for' => 'admin',

            ],

            //store miscellaneous payment permissions
            [
                'name' => 'View Store Miscellaneous Payment List',
                'category' => 'Store Miscellaneous Payment',
                'description' => 'can view Store Miscellaneous Payment list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Miscellaneous Payment',
                'category' => 'Store Miscellaneous Payment',
                'description' => 'can view single Store Miscellaneous Payment detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Store Miscellaneous Payment',
                'category' => 'Store Miscellaneous Payment',
                'description' => 'can verify Store Miscellaneous Payment',
                'permission_for' => 'admin',

            ],
            //store offline order payment permissions
            [
                'name' => 'View Store Order Offline Payment List',
                'category' => 'Store Order Offline Payment',
                'description' => 'can view Store Order Offline Payment list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Order Offline Payment',
                'category' => 'Store Order Offline Payment',
                'description' => 'can view single Store Order Offline Payment detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Store Order Offline Payment',
                'category' => 'Store Order Offline Payment',
                'description' => 'can verify Store Order Offline Payment',
                'permission_for' => 'admin',

            ],

            //store kyc permissions
            [
                'name' => 'View Store Individual Kyc List',
                'category' => 'Store Kyc',
                'description' => 'can view Store Individual Kyc list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Individual Kyc',
                'category' => 'Store Kyc',
                'description' => 'can view single Store Individual Kyc detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Store Individual Kyc',
                'category' => 'Store Kyc',
                'description' => 'can verify Store Individual Kyc',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Store Firm Kyc List',
                'category' => 'Store Kyc',
                'description' => 'can view Store Firm Kyc list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Firm Kyc',
                'category' => 'Store Kyc',
                'description' => 'can view single Store Firm Kyc detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Store Firm Kyc',
                'category' => 'Store Kyc',
                'description' => 'can verify Store Firm Kyc',
                'permission_for' => 'admin',

            ],
            //Bank permissions
            [
                'name' => 'Create Bank',
                'category' => 'Bank',
                'description' => 'can add Bank',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Bank List',
                'category' => 'Bank',
                'description' => 'can view Bank list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Bank',
                'category' => 'Bank',
                'description' => 'can view single Bank detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Bank',
                'category' => 'Bank',
                'description' => 'can update Bank',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Bank',
                'category' => 'Bank',
                'description' => 'can delete Bank',
                'permission_for' => 'admin',

            ],
            //Brand permissions
            [
                'name' => 'Create Brand',
                'category' => 'Brand',
                'description' => 'can add Brand',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Brand List',
                'category' => 'Brand',
                'description' => 'can view Brand list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Brand',
                'category' => 'Brand',
                'description' => 'can view single Brand detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Brand',
                'category' => 'Brand',
                'description' => 'can update Brand',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Brand',
                'category' => 'Brand',
                'description' => 'can delete Brand',
                'permission_for' => 'admin',

            ],
            //Category permissions
            [
                'name' => 'Create Category',
                'category' => 'Category',
                'description' => 'can add Category',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Category List',
                'category' => 'Category',
                'description' => 'can view Category list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Category',
                'category' => 'Category',
                'description' => 'can view single Category detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Category',
                'category' => 'Category',
                'description' => 'can update Category',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Category',
                'category' => 'Category',
                'description' => 'can delete Category',
                'permission_for' => 'admin',

            ],
            //Category Brand permissions
            [
                'name' => 'Create Category Brand',
                'category' => 'Category Brand',
                'description' => 'can add Category Brand',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Category Brand List',
                'category' => 'Category Brand',
                'description' => 'can view Category Brand list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Category Brand',
                'category' => 'Category Brand',
                'description' => 'can view single Category Brand detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Category Brand',
                'category' => 'Category Brand',
                'description' => 'can update Category Brand',
                'permission_for' => 'admin',

            ],
            //Variant permissions
            [
                'name' => 'Create Variant',
                'category' => 'Variant',
                'description' => 'can add Variant',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Variant List',
                'category' => 'Variant',
                'description' => 'can view Variant list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Variant',
                'category' => 'Variant',
                'description' => 'can view single Variant detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Variant',
                'category' => 'Variant',
                'description' => 'can update Variant',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Variant',
                'category' => 'Variant',
                'description' => 'can Delete Variant',
                'permission_for' => 'admin',

            ],
            //Variant value permissions
            [
                'name' => 'Create Variant Value',
                'category' => 'Variant Value',
                'description' => 'can add Variant Value',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Variant Value List',
                'category' => 'Variant Value',
                'description' => 'can view Variant Value list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Variant Value',
                'category' => 'Variant Value',
                'description' => 'can view single Variant Value detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Variant Value',
                'category' => 'Variant Value',
                'description' => 'can update Variant Value',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Variant Value',
                'category' => 'Variant Value',
                'description' => 'can Delete Variant Value',
                'permission_for' => 'admin',
            ],

            //Product permissions
            [
                'name' => 'View Product List',
                'category' => 'Product',
                'description' => 'can view Product list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Product',
                'category' => 'Product',
                'description' => 'can view single Product detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Verify Product',
                'category' => 'Product',
                'description' => 'can verify Product',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Product Status',
                'category' => 'Product',
                'description' => 'can update Product status',
                'permission_for' => 'admin',

            ],



            //Enquiry permissions
            [
                'name' => 'View Contact Message List',
                'category' => 'Enquiry',
                'description' => 'can view Contact Message list',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Contact Message',
                'category' => 'Enquiry',
                'description' => 'can view single contact message detail',
                'permission_for' => 'admin',
            ],
            //faq content mgmt permissions
            [
                'name' => 'Create Faq',
                'category' => 'Content Management',
                'description' => 'can add faq',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Faq List',
                'category' => 'Content Management',
                'description' => 'can view Faq list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Faq',
                'category' => 'Content Management',
                'description' => 'can view single Faq detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Faq',
                'category' => 'Content Management',
                'description' => 'can update Faq',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Faq',
                'category' => 'Content Management',
                'description' => 'can Delete Faq',
                'permission_for' => 'admin',

            ],
            //site pages content mgmt permissions
            [
                'name' => 'Create Site Page',
                'category' => 'Content Management',
                'description' => 'can add faq',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Site Page List',
                'category' => 'Content Management',
                'description' => 'can view Site Page list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Site Page',
                'category' => 'Content Management',
                'description' => 'can view single Site Page detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Site Page',
                'category' => 'Content Management',
                'description' => 'can update Site Page',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Site Page',
                'category' => 'Content Management',
                'description' => 'can Delete Site Page',
                'permission_for' => 'admin',

            ],
            //Parameterization vendor permissions
            [
                'name' => 'Create Vendor Type',
                'category' => 'Parameterization Vendor Type',
                'description' => 'can add Vendor Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Vendor Type List',
                'category' => 'Parameterization Vendor Type',
                'description' => 'can view Vendor Type list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Vendor Type',
                'category' => 'Parameterization Vendor Type',
                'description' => 'can view single Vendor Type detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Vendor Type',
                'category' => 'Parameterization Vendor Type',
                'description' => 'can update Vendor Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Vendor Type',
                'category' => 'Parameterization Vendor Type',
                'description' => 'can Delete Vendor Type',
                'permission_for' => 'admin',
            ],
            //Parameterization Company Type permissions
            [
                'name' => 'Create Company Type',
                'category' => 'Parameterization Company Type',
                'description' => 'can add Company Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Company Type List',
                'category' => 'Parameterization Company Type',
                'description' => 'can view Company Type list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Company Type',
                'category' => 'Parameterization Company Type',
                'description' => 'can view single Company Type detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Company Type',
                'category' => 'Parameterization Company Type',
                'description' => 'can update Company Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Company Type',
                'category' => 'Parameterization Company Type',
                'description' => 'can Delete Company Type',
                'permission_for' => 'admin',
            ],
            //Parameterization Registration Type permissions
            [
                'name' => 'Create Registration Type',
                'category' => 'Parameterization Registration Type',
                'description' => 'can add Registration Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Registration Type List',
                'category' => 'Parameterization Registration Type',
                'description' => 'can view Registration Type list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Registration Type',
                'category' => 'Parameterization Registration Type',
                'description' => 'can view single Registration Type detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Registration Type',
                'category' => 'Parameterization Registration Type',
                'description' => 'can update Registration Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Registration Type',
                'category' => 'Parameterization Registration Type',
                'description' => 'can Delete Registration Type',
                'permission_for' => 'admin',
            ],
            //Parameterization Category Type permissions
            [
                'name' => 'Create Category Type',
                'category' => 'Parameterization Category Type',
                'description' => 'can add Category Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Category Type List',
                'category' => 'Parameterization Category Type',
                'description' => 'can view Category Type list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Category Type',
                'category' => 'Parameterization Category Type',
                'description' => 'can view single Category Type detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Category Type',
                'category' => 'Parameterization Category Type',
                'description' => 'can update Category Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Category Type',
                'category' => 'Parameterization Category Type',
                'description' => 'can Delete Category Type',
                'permission_for' => 'admin',
            ],
            //Parameterization Cancellation Parameter permissions
            [
                'name' => 'Create Cancellation Parameter',
                'category' => 'Parameterization Cancellation Parameter',
                'description' => 'can add Cancellation Parameter',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Cancellation Parameter List',
                'category' => 'Parameterization Cancellation Parameter',
                'description' => 'can view Cancellation Parameter list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Cancellation Parameter',
                'category' => 'Parameterization Cancellation Parameter',
                'description' => 'can view single Cancellation Parameter detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Cancellation Parameter',
                'category' => 'Parameterization Cancellation Parameter',
                'description' => 'can update Cancellation Parameter',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Cancellation Parameter',
                'category' => 'Parameterization Cancellation Parameter',
                'description' => 'can Delete Cancellation Parameter',
                'permission_for' => 'admin',
            ],
            //Parameterization Rejection Parameter permissions
            [
                'name' => 'Create Rejection Parameter',
                'category' => 'Parameterization Rejection Parameter',
                'description' => 'can add Rejection Parameter',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Rejection Parameter List',
                'category' => 'Parameterization Rejection Parameter',
                'description' => 'can view Rejection Parameter list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Rejection Parameter',
                'category' => 'Parameterization Rejection Parameter',
                'description' => 'can view single Rejection Parameter detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Rejection Parameter',
                'category' => 'Parameterization Rejection Parameter',
                'description' => 'can update Rejection Parameter',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Rejection Parameter',
                'category' => 'Parameterization Rejection Parameter',
                'description' => 'can Delete Rejection Parameter',
                'permission_for' => 'admin',
            ],
            //Parameterization Package Type permissions
            [
                'name' => 'Create Package Type',
                'category' => 'Parameterization Package Type',
                'description' => 'can add Package Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Package Type List',
                'category' => 'Parameterization Package Type',
                'description' => 'can view Package Type list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Package Type',
                'category' => 'Parameterization Package Type',
                'description' => 'can view single Package Type detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Package Type',
                'category' => 'Parameterization Package Type',
                'description' => 'can update Package Type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Package Type',
                'category' => 'Parameterization Package Type',
                'description' => 'can Delete Package Type',
                'permission_for' => 'admin',
            ],
            //Parameterization Product Sensitivity permissions
            [
                'name' => 'Create Product Sensitivity',
                'category' => 'Parameterization Product Sensitivity',
                'description' => 'can add Product Sensitivity',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Product Sensitivity List',
                'category' => 'Parameterization Product Sensitivity',
                'description' => 'can view Product Sensitivity list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Product Sensitivity',
                'category' => 'Parameterization Product Sensitivity',
                'description' => 'can view single Product Sensitivity detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Product Sensitivity',
                'category' => 'Parameterization Product Sensitivity',
                'description' => 'can update Product Sensitivity',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Product Sensitivity',
                'category' => 'Parameterization Product Sensitivity',
                'description' => 'can Delete Product Sensitivity',
                'permission_for' => 'admin',
            ],
            //Parameterization Product Warranty permissions
            [
                'name' => 'Create Product Warranty',
                'category' => 'Parameterization Product Warranty',
                'description' => 'can add Product Warranty',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Product Warranty List',
                'category' => 'Parameterization Product Warranty',
                'description' => 'can view Product Warranty list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Product Warranty',
                'category' => 'Parameterization Product Warranty',
                'description' => 'can view single Product Warranty detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Product Warranty',
                'category' => 'Parameterization Product Warranty',
                'description' => 'can update Product Warranty',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Product Warranty',
                'category' => 'Parameterization Product Warranty',
                'description' => 'can Delete Product Warranty',
                'permission_for' => 'admin',
            ],
            //Parameterization Store Size permissions
            [
                'name' => 'Create Store Size',
                'category' => 'Parameterization Store Size',
                'description' => 'can add Store Size',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Store Size List',
                'category' => 'Parameterization Store Size',
                'description' => 'can view Store Size list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Size',
                'category' => 'Parameterization Store Size',
                'description' => 'can view single Store Size detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Store Size',
                'category' => 'Parameterization Store Size',
                'description' => 'can update Store Size',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Store Size',
                'category' => 'Parameterization Store Size',
                'description' => 'can Delete Store Size',
                'permission_for' => 'admin',
            ],
            //Product Collection permissions
            [
                'name' => 'Create Product Collection',
                'category' => 'Product Collection',
                'description' => 'can add Product Collection',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Product Collection List',
                'category' => 'Product Collection',
                'description' => 'can view Product Collection list',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Product Collection',
                'category' => 'Product Collection',
                'permission_for' => 'admin',
                'description' => 'can view single Product Collection detail',

            ],
            [
                'name' => 'Update Product Collection',
                'category' => 'Product Collection',
                'permission_for' => 'admin',
                'description' => 'can update Product Collection',

            ],
            [
                'name' => 'Delete Product Collection',
                'category' => 'Product Collection',
                'permission_for' => 'admin',
                'description' => 'can Delete Product Collection',
            ],
            //Sliders permissions
            [
                'name' => 'Create Slider',
                'category' => 'Slider',
                'permission_for' => 'admin',
                'description' => 'can add Slider',

            ],
            [
                'name' => 'View Slider List',
                'category' => 'Slider',
                'permission_for' => 'admin',
                'description' => 'can view Slider list',

            ],
            [
                'name' => 'Show Slider',
                'category' => 'Slider',
                'permission_for' => 'admin',
                'description' => 'can view single Slider detail',

            ],
            [
                'name' => 'Update Slider',
                'category' => 'Slider',
                'permission_for' => 'admin',
                'description' => 'can update Slider',

            ],
            [
                'name' => 'Delete Slider',
                'category' => 'Slider',
                'permission_for' => 'admin',
                'description' => 'can Delete Slider',
            ],
            //System Setting permissions
            [
                'name' => 'View General Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can view general setting',

            ],
            [
                'name' => 'Update General Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can update general setting',

            ],
            [
                'name' => 'View Mail Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can view Mail setting',

            ],
            [
                'name' => 'Update Mail Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can update Mail setting',

            ],
            [
                'name' => 'View Passport Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can view Passport setting',

            ],
            [
                'name' => 'Update Passport Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can update Passport setting',

            ],
            [
                'name' => 'View Site Url Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can view Site Url setting',

            ],
            [
                'name' => 'Update Site Url Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can update Site Url setting',

            ],
            [
                'name' => 'View Seo Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can view Seo setting',

            ],
            [
                'name' => 'Update Seo Setting',
                'category' => 'System Setting',
                'permission_for' => 'admin',
                'description' => 'can update Seo setting',

            ],
            //Newsletter permissions
            [
                'name' => 'Create Newsletter',
                'category' => 'Newsletter',
                'permission_for' => 'admin',
                'description' => 'can add Newsletter',

            ],
            [
                'name' => 'View Newsletter List',
                'category' => 'Newsletter',
                'permission_for' => 'admin',
                'description' => 'can view Newsletter list',

            ],
            [
                'name' => 'Show Newsletter',
                'category' => 'Newsletter',
                'permission_for' => 'admin',
                'description' => 'can view single Newsletter detail',

            ],
            [
                'name' => 'Update Newsletter',
                'category' => 'Newsletter',
                'permission_for' => 'admin',
                'description' => 'can update Newsletter',

            ],
            [
                'name' => 'Delete Newsletter',
                'category' => 'Newsletter',
                'permission_for' => 'admin',
                'description' => 'can Delete Newsletter',
            ],
            //Subscribers permissions
            [
                'name' => 'View Subscriber List',
                'category' => 'Subscriber',
                'permission_for' => 'admin',
                'description' => 'can view Subscriber list',

            ],
            [
                'name' => 'Update Subscriber Status',
                'category' => 'Subscriber',
                'permission_for' => 'admin',
                'description' => 'can update Subscriber Status',

            ],
            [
                'name' => 'Delete Subscriber',
                'category' => 'Subscriber',
                'permission_for' => 'admin',
                'description' => 'can Delete Subscriber',
            ],

            //Warehouse permissions
            [
                'name' => 'Create Warehouse',
                'category' => 'Warehouse',
                'description' => 'can add Warehouse',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Warehouse List',
                'category' => 'Warehouse',
                'description' => 'can view Warehouse lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Warehouse',
                'category' => 'Warehouse',
                'description' => 'can view single Warehouse detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Warehouse',
                'category' => 'Warehouse',
                'description' => 'can update Warehouse',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Warehouse',
                'category' => 'Warehouse',
                'description' => 'can delete Warehouse',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Warehouse Status',
                'category' => 'Warehouse',
                'description' => 'can update Warehouse status',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change WH Admin Password AdminSide',
                'category' => 'Warehouse',
                'description' => 'can change Warehouse password',
                'permission_for' => 'admin',
            ],

            //Location permissions
            [
                'name' => 'Create Location',
                'category' => 'Location',
                'description' => 'can add Location',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Location List',
                'category' => 'Location',
                'description' => 'can view Location lists',
                'permission_for' => 'admin',

            ],
            //Store Enquiry Message permissions
            [
            'name' => 'Reply Store Enquiry Message',
            'category' => 'Store Enquiry Message',
            'description' => 'can reply Store Enquiry Message',
            'permission_for' => 'admin',
            ],
            [
                'name' => 'View Store Enquiry Message List',
                'category' => 'Store Enquiry Message',
                'description' => 'can view Store Enquiry Message lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Enquiry Message',
                'category' => 'Store Enquiry Message',
                'description' => 'can view single Store Enquiry Message detail',
                'permission_for' => 'admin',

            ],

            //Global Notification permissions
            [
                'name' => 'Create Global Notification',
                'category' => 'Global Notification',
                'description' => 'can add Global Notification',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Global Notification List',
                'category' => 'Global Notification',
                'description' => 'can view Global Notification lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Global Notification',
                'category' => 'Global Notification',
                'description' => 'can view single Global Notification detail',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Global Notification',
                'category' => 'Global Notification',
                'description' => 'can update Global Notification',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Global Notification',
                'category' => 'Global Notification',
                'description' => 'can delete Global Notification',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Global Notification status',
                'category' => 'Global Notification',
                'description' => 'can update Global Notification status',
                'permission_for' => 'admin',
            ],
            //Warehouse Product Collection permissions
            [
                'name' => 'Create WH Product Collection',
                'category' => 'WH Product Collection',
                'description' => 'can add WH Product Collection',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'View WH Product Collection List',
                'category' => 'WH Product Collection',
                'description' => 'can view WH Product Collection lists',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Show WH Product Collection',
                'category' => 'WH Product Collection',
                'description' => 'can view single WH Product Collection detail',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Update WH Product Collection',
                'category' => 'WH Product Collection',
                'description' => 'can update WH Product Collection',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Delete WH Product Collection',
                'category' => 'WH Product Collection',
                'description' => 'can delete WH Product Collection',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Add Products In WH Product Collection',
                'category' => 'WH Product Collection',
                'description' => 'can add Products In WH Product Collection',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change WH Product Collection Status',
                'category' => 'WH Product Collection',
                'description' => 'can change WH Product Collection status',
                'permission_for' => 'warehouse',
            ],

           // product collection add product and change status
            [
                'name' => 'Add Products In Product Collection',
                'category' => 'Product Collection',
                'description' => 'can add Product In Product Collection',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change Product Collection Status',
                'category' => 'Product Collection',
                'description' => 'can change Product Collection status',
                'permission_for' => 'warehouse',
            ],
            //Store Balance Reconciliation permissions
            [
                'name' => 'Create Store Balance Reconciliation',
                'category' => 'Store Balance Reconciliation',
                'description' => 'can add Store Balance Reconciliation',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'View Store Balance Reconciliation List',
                'category' => 'Store Balance Reconciliation',
                'description' => 'can view Store Balance Reconciliation lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Store Balance Reconciliation',
                'category' => 'Store Balance Reconciliation',
                'description' => 'can update Store Balance Reconciliation',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Balance Reconciliation',
                'category' => 'Store Balance Reconciliation',
                'description' => 'can view single Store Balance Reconciliation',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Change Store Balance Reconciliation Status',
                'category' => 'Store Balance Reconciliation',
                'description' => 'can change the status of Store Balance Reconciliation',
                'permission_for' => 'admin',

            ],
            //Store Types
            [
                'name' => 'View Store Type List',
                'category' => 'Store Type',
                'description' => 'can view store type lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Create Store Type',
                'category' => 'Store Type',
                'description' => 'can create store type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Store Type',
                'category' => 'Store Type',
                'description' => 'can view single store type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Update Store Type',
                'category' => 'Store Type',
                'description' => 'can update store type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Delete Store Type',
                'category' => 'Store Type',
                'description' => 'can delete store type',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Change Store Type Status',
                'category' => 'Store Type',
                'description' => 'can change store type status',
                'permission_for' => 'admin',

            ],
            //Warehouse Stock Transfer Permissions
            [
                'name' => 'View WH Stock Transfer List',
                'category' => 'WH Stock Transfer',
                'description' => 'can view wh stock transfer list',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Create WH Stock Transfer',
                'category' => 'WH Stock Transfer',
                'description' => 'can create wh stock transfer',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'View Received WH Stock Transfer List',
                'category' => 'WH Stock Transfer',
                'description' => 'can view received wh stock transfer',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Update Received WH Stock Transfer Products Quantity',
                'category' => 'WH Stock Transfer',
                'description' => 'can update received wh stock transfer products quantity',
                'permission_for' => 'warehouse',

            ],
//            [
//                'name' => 'View Unapproved Stores List',
//                'category' => 'Store',
//                'description' => 'can view unapproved stores list',
//                'permission_for' => 'admin',
//
//            ],
//            [
//                'name' => 'Change Status of Unapproved Store',
//                'category' => 'Store',
//                'description' => 'can change the status of unapproved store',
//                'permission_for' => 'admin',
//
//            ],
            // Warehouse PreOrder
            [
                'name' => 'View List of WH Pre Orders',
                'category' => 'WH PreOrder',
                'description' => 'can view lists of warehouse pre orders',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Create WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can create warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Edit WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can edit warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Delete WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can edit warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Cancel WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can cancel warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Finalize WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can finalize warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change the Status of WH Pre Order',
                'category' => 'WH PreOrder',
                'description' => 'can change status of  warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            // Warehouse PreOrder Product
            [
                'name' => 'View Added Products of WH Pre Order',
                'category' => 'WH PreOrder Product',
                'description' => 'can view added products of warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Add Products to WH Pre Order',
                'category' => 'WH PreOrder Product',
                'description' => 'can add products in warehouse pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change Status Of Products Of Vendor',
                'category' => 'WH PreOrder Product',
                'description' => 'can change status of products of vendor',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change Status Of Variants of Product',
                'category' => 'WH PreOrder Product',
                'description' => 'can change status of variants of product',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Clone Warehouse Products',
                'category' => 'WH PreOrder Product',
                'description' => 'can clone products from different warehouse',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Clone Vendor Products',
                'category' => 'WH PreOrder Product',
                'description' => 'can clone products from vendor',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Edit Product Variant Price of Pre Order',
                'category' => 'WH PreOrder Product',
                'description' => 'can edit product variant price of pre order',
                'permission_for' => 'warehouse',
            ],
            // Warehouse Purchase PreOrder
            [
                'name' => 'View List Of Vendors For Pre Orders',
                'category' => 'WH Purchase PreOrder',
                'description' => 'can view list of vendors for pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Place Order For Pre Order',
                'category' => 'WH Purchase PreOrder',
                'description' => 'can place order for pre order to vendor',
                'permission_for' => 'warehouse',
            ],
            // Warehouse Store PreOrder
            [
                'name' => 'View Store Pre Orders In Pre Order',
                'category' => 'WH Store PreOrder',
                'description' => 'can view store pre orders in pre order',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'View List Of Store Pre Orders',
                'category' => 'WH Store PreOrder',
                'description' => 'can view lists of store pre orders in warehouse',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'View Store Pre Order Details',
                'category' => 'WH Store PreOrder',
                'description' => 'can view store pre orders details in warehouse',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Update Pre Order Status',
                'category' => 'WH Store PreOrder',
                'description' => 'can update store pre orders status in warehouse',
                'permission_for' => 'warehouse',
            ],


            // PreOrder Target
            [
                'name' => 'Create PreOrder Target',
                'category' => 'PreOrder Target',
                'description' => 'can create preorder target',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Show PreOrder Target',
                'category' => 'PreOrder Target',
                'description' => 'can view preorder target',
                'permission_for' => 'warehouse',
            ],
            //Warehouse Bill Merge
            [
                'name' => 'View Bill Merge Master List',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can view bill merge master list',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Create Bill Merge',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can create bill merge',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Show Bill Merge Products',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can view bill merged products',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Update Bill Merge Product Detail',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can update bill merged products details',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Update Bill Merge Master Status',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can update bill merged master status',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Bill Merge Generate Bill',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can download bill merge pdf',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Show Bill Merge Order Details',
                'category' => 'Warehouse Bill Merge',
                'description' => 'can view bill merge order details',
                'permission_for' => 'warehouse',
            ],
            // warehouse dashboard
            [
                'name' => 'View Warehouse Dashboard',
                'category' => 'Warehouse Dashboard',
                'description' => 'can view warehouse dashboard',
                'permission_for' => 'warehouse',
            ],
            //Warehouse Purchase Order
            [
                'name' => 'View List of WH Purchase Orders',
                'category' => 'WH Purchase Order',
                'description' => 'can view lists of warehouse purchase orders',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Add New WH Purchase Order',
                'category' => 'WH Purchase Order',
                'description' => 'can create warehouse purchase orders',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Show WH Purchase Order Detail',
                'category' => 'WH Purchase Order',
                'description' => 'can view warehouse purchase orders details',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Return the Purchase item',
                'category' => 'WH Purchase Order',
                'description' => 'can return warehouse purchase orders items',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'View Receive Purchased Stock',
                'category' => 'WH Purchase Order',
                'description' => 'can view warehouse recieve purchased stock items',
                'permission_for' => 'warehouse',
            ],
            //Invoice Setting
            [
                'name' => 'View Invoice Setting Lists',
                'category' => 'Warehouse Invoice Setting',
                'description' => 'can view invoice setting lists',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Create Invoice Setting',
                'category' => 'Warehouse Invoice Setting',
                'description' => 'can create invoice setting ',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Update Invoice Setting',
                'category' => 'Warehouse Invoice Setting',
                'description' => 'can update invoice setting ',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Delete Invoice Setting',
                'category' => 'Warehouse Invoice Setting',
                'description' => 'can delete invoice setting',
                'permission_for' => 'warehouse',
            ],
            // Warehouse Product
            [
                'name' => 'View List Of Wh Products',
                'category' => 'Warehouse Product',
                'description' => 'can view warehouse product',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'View WH Product Detail',
                'category' => 'Warehouse Product',
                'description' => 'can view warehouse product detail',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change WH Product Status',
                'category' => 'Warehouse Product',
                'description' => 'can change warehouse product status',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Price Setting for WH Product',
                'category' => 'Warehouse Product',
                'description' => 'can set price of warehouse product',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Set Quantity Limit for WH Product',
                'category' => 'Warehouse Product',
                'description' => 'can set quantity limit of warehouse product',
                'permission_for' => 'warehouse',
            ],
            // Warehouse Store Connection
            [
                'name' => 'View WH Store Connection',
                'category' => 'Warehouse Store Connection',
                'description' => 'can view warehouse store connection',
                'permission_for' => 'warehouse',
            ],
            // Sales Manager Permission
            [
                'name' => 'View Manager Lists',
                'category' => 'Sales Manager',
                'description' => 'can view sales manager',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Manager',
                'category' => 'Sales Manager',
                'description' => 'can view sales manager detail',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Manager Status',
                'category' => 'Sales Manager',
                'description' => 'can change manager status',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Assign Store',
                'category' => 'Sales Manager',
                'description' => 'can assign store',
                'permission_for' => 'admin',
            ],
//            [
//                'name' => 'Assign Store',
//                'category' => 'Sales Manager',
//                'description' => 'can assign store',
//            ],
            [
                'name' => 'View All Referred Store',
                'category' => 'Sales Manager',
                'description' => 'can view all referred store',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Manager Password',
                'category' => 'Sales Manager',
                'description' => 'can update manager password',
                'permission_for' => 'admin',
            ],

            [
                'name' => 'Assign Stores To Manager',
                'category' => 'Sales Manager',
                'description' => 'can assign manager with store',
                'permission_for' => 'admin',
            ],

            [
                'name' => 'View All Assigned Store',
                'category' => 'Sales Manager',
                'description' => 'can view all assigned stores',
                'permission_for' => 'admin',
            ],

            [
                'name' => 'Unlink Store From Manager',
                'category' => 'Sales Manager',
                'description' => 'can unlink store from manager',
                'permission_for' => 'admin',
            ],


            // B2C User Permission
            [
                'name' => 'View Customer Lists',
                'category' => 'B2C User',
                'description' => 'can view customer lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Customer',
                'category' => 'B2C User',
                'description' => 'can view customer detail',
                'permission_for' => 'admin',

            ],
            // Investment Plan Permission
            [
                'name' => 'View Investment Plan Lists',
                'category' => 'Investment Plan',
                'description' => 'can view investment plan lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Investment Plan',
                'category' => 'Investment Plan',
                'description' => 'can create investment plan',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Investment Plan',
                'category' => 'Investment Plan',
                'description' => 'can view investment plan detail',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Investment Plan',
                'category' => 'Investment Plan',
                'description' => 'can update investment plan',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Investment Plan Status',
                'category' => 'Investment Plan',
                'description' => 'can change investment plan status',
                'permission_for' => 'admin',
            ],
            // Investment Plan Commission Permission
            [
                'name' => 'View Investment Plan Commission Lists',
                'category' => 'Investment Plan Commission',
                'description' => 'can view investment plan commission lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Investment Plan Commission',
                'category' => 'Investment Plan Commission',
                'description' => 'can create investment plan commission',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Investment Plan Commission',
                'category' => 'Investment Plan Commission',
                'description' => 'can update investment plan commission',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Investment Plan Commission Status',
                'category' => 'Investment Plan Commission',
                'description' => 'can change investment plan commission status',
                'permission_for' => 'admin',
            ],
            // Investment Plan Interest Release Option Permission
            [
                'name' => 'View Investment Plan Interest Release Option Lists',
                'category' => 'Investment Plan Interest Release Option',
                'description' => 'can view investment plan interest release option lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Investment Plan Interest Release Option',
                'category' => 'Investment Plan Interest Release Option',
                'description' => 'can create investment plan interest release option',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Investment Plan Interest Release Option',
                'category' => 'Investment Plan Interest Release Option',
                'description' => 'can update investment plan interest release option',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Investment Plan Interest Release Option Status',
                'category' => 'Investment Plan Interest Release Option',
                'description' => 'can change investment plan interest release option status',
                'permission_for' => 'admin',
            ],
            // Investment Plan Subscription Permission
            [
                'name' => 'View Investment Plan Subscription Lists',
                'category' => 'Investment Plan Subscription',
                'description' => 'can view investment plan subscription lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'View Investment Plan Subscription Details',
                'category' => 'Investment Plan Subscription',
                'description' => 'can view investment plan subscrption details',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Investment Plan Subscription',
                'category' => 'Investment Plan Subscription',
                'description' => 'can show investment plan subscription detail',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Respond Investment Plan Subscription',
                'category' => 'Investment Plan Subscription',
                'description' => 'can respond investment plan subscription',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Investment Plan Subscription Status',
                'category' => 'Investment Plan Subscription',
                'description' => 'can change investment plan subscription status',
                'permission_for' => 'admin',
            ],
            // Store Type Package Permission
            [
                'name' => 'View Store Type Package Lists',
                'category' => 'Store Type Package',
                'description' => 'can view store type package lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Show Store Type Package',
                'category' => 'Store Type Package',
                'description' => 'can view store type package detail',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Store Type Package',
                'category' => 'Store Type Package',
                'description' => 'can create store type package',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Store Type Package',
                'category' => 'Store Type Package',
                'description' => 'can update store type package',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Store Type Package Status',
                'category' => 'Store Type Package',
                'description' => 'can change store type package status',
                'permission_for' => 'admin',
            ],
            // Preorder Reporting Permission
            [
                'name' => 'View Preorder Reporting',
                'category' => 'Preorder Reporting',
                'description' => 'can view preorder reporting',
                'permission_for' => 'admin',
            ],
            // Online Payment Permission
            [
                'name' => 'View Online Payment Lists',
                'category' => 'Online Payment',
                'description' => 'can view online payment lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Verify Online Payment',
                'category' => 'Online Payment',
                'description' => 'can verify online payment',
                'permission_for' => 'admin',
            ],

            // Wallet Manager Transaction Permission
            [
                'name' => 'View All Transaction Lists By Wallet Code',
                'category' => 'Wallet Manager Transaction',
                'description' => 'can view all transaction lists',
                'permission_for' => 'admin',
            ],
            // Wallet Store Transaction Permission
            [
                'name' => 'View Store Wallet Transaction Detail',
                'category' => 'Wallet Store Transaction',
                'description' => 'can view wallet store transaction',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Store Wallet Transaction',
                'category' => 'Wallet Store Transaction',
                'description' => 'can create store wallet transaction',
                'permission_for' => 'admin',
            ],
            // Vendor Wallet Transaction Permission
            [
                'name' => 'View Vendor Wallet Transaction Detail',
                'category' => 'Wallet Vendor Transaction',
                'description' => 'can view vendor wallet transaction detail',
                'permission_for' => 'admin',
            ],
            // Wallet Permission
            [
                'name' => 'View Wallet Lists',
                'category' => 'Wallet',
                'description' => 'can view wallet lists',
                'permission_for' => 'admin',
            ],
            // Wallet Transaction Purpose Permission
            [
                'name' => 'View Wallet Transaction Purpose Lists',
                'category' => 'Wallet Transaction Purpose',
                'description' => 'can view wallet transaction purpose lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Wallet Transaction Purpose',
                'category' => 'Wallet Transaction Purpose',
                'description' => 'can create wallet transaction purpose',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Wallet Transaction Purpose',
                'category' => 'Wallet Transaction Purpose',
                'description' => 'can update wallet transaction purpose',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Delete Wallet Transaction Purpose',
                'category' => 'Wallet Transaction Purpose',
                'description' => 'can delete wallet transaction purpose',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Wallet Transaction Purpose Status',
                'category' => 'Wallet Transaction Purpose',
                'description' => 'can change wallet transaction purpose status',
                'permission_for' => 'admin',
            ],
            // Pricing Link Permission
            [
                'name' => 'View Pricing Link Lists',
                'category' => 'Pricing Link',
                'description' => 'can view pricing link lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Create Pricing Link',
                'category' => 'Pricing Link',
                'description' => 'can create pricing link',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Update Pricing Link',
                'category' => 'Pricing Link',
                'description' => 'can update pricing link',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Change Pricing Link Status',
                'category' => 'Pricing Link',
                'description' => 'can change pricing link status',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'View Pricing Link Lead Lists',
                'category' => 'Pricing Link',
                'description' => 'can view pricing link lead lists',
                'permission_for' => 'admin',
            ],
            // Admin Warehouse PreOrder
            [
                'name' => 'View Warehouse Having Pre Orders',
                'category' => 'Admin WH PreOrder',
                'description' => 'can view wh pre orders',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'View List Of Pre Orders',
                'category' => 'Admin WH PreOrder',
                'description' => 'can view lists of pre orders in warehouse',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'View Vendor Lists Pre Order Of Warehouse Having Pre Orders',
                'category' => 'Admin WH PreOrder',
                'description' => 'can view list of vendors of warehouse having pre orders',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'Clone Admin Warehouse Products',
                'category' => 'Admin WH PreOrder',
                'description' => 'can clone products from different warehouse',
                'permission_for' => 'admin',
            ],
           // Admin Store Balance Withdraw
            [
                'name' => 'View Store Balance Withdraw List',
                'category' => 'Store Balance Withdraw',
                'description' => 'can view store balance withdraw lists',
                'permission_for' => 'admin',
            ],
            [
                'name' => 'View Store Balance Withdraw Detail',
                'category' => 'Store Balance Withdraw',
                'description' => 'can view store balance withdraw detail',
                'permission_for' => 'admin',
            ],
            // Warehouse User
            [
                'name' => 'View List Of Wh Users',
                'category' => 'Warehouse User',
                'description' => 'can view warehouse user',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Create WH User',
                'category' => 'Warehouse User',
                'description' => 'can create warehouse user',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Change WH User Status',
                'category' => 'Warehouse User',
                'description' => 'can change warehouse user status',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Update WH User',
                'category' => 'Warehouse User',
                'description' => 'can update warehouse user',
                'permission_for' => 'warehouse',
            ],
            [
                'name' => 'Delete WH User',
                'category' => 'Warehouse User',
                'description' => 'can delete warehouse user',
                'permission_for' => 'warehouse',
            ],
            //WH store order permissions
            [
                'name' => 'View WH Store Order List',
                'category' => 'WH Store Order',
                'description' => 'can view Store Order list',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Show WH Store Order',
                'category' => 'WH Store Order',
                'description' => 'can view single Store Order detail',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Change Status Of WH Store Order',
                'category' => 'WH Store Order',
                'description' => 'can change status of warehouse store order',
                'permission_for' => 'warehouse',

            ],
            //Warehouse Vendor Wise Current Stock Permissions
            [
                'name' => 'View WH Vendor Wise Current Stock List',
                'category' => 'WH Current Stock',
                'description' => 'can view wh current stock lists',
                'permission_for' => 'warehouse',

            ],
            [
                'name' => 'Show WH Vendor Wise Current Stock Detail',
                'category' => 'WH Current Stock',
                'description' => 'can view vendor wise current stock detail',
                'permission_for' => 'warehouse',

            ],
            //Admin Warehouse Wise Current Stock Permissions
            [
                'name' => 'View Warehouse Wise Current Stock List',
                'category' => 'Admin Current Stock',
                'description' => 'can view admin current stock lists',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Admin Vendor Wise Current Stock',
                'category' => 'Admin Current Stock',
                'description' => 'can view vendor wise current stock',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Show Admin Product Current Stock',
                'category' => 'Admin Current Stock',
                'description' => 'can view product current stock',
                'permission_for' => 'admin',

            ],
            //Admin Stock Transfer Permission
            [
                'name' => 'View Admin Stock Transfer',
                'category' => 'Admin Stock Transfer',
                'description' => 'can view admin stock transfer',
                'permission_for' => 'admin',

            ],
            [
                'name' => 'Save Admin Stock Transfer',
                'category' => 'Admin Stock Transfer',
                'description' => 'can save admin stock transfer',
                'permission_for' => 'admin',

            ],
        ];

        $permissions = collect($arrayOfPermissions)->map(function ($permission) use($defaultGuard) {
            return ['name' => ucwords($permission['name']),
                'slug' =>Str::slug($permission['name'],'-'),
                'category' => ucwords($permission['category']),
                'description' => strtolower($permission['description']),
                'permission_for' => strtolower($permission['permission_for']),
                'guard_name' => $defaultGuard,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        });

        //\App\Models\Permission::insert($permissions->toArray());
        \Spatie\Permission\Models\Permission::insert($permissions->toArray());

    }
}
