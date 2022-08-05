<?php

namespace  App\Modules\ContentManagement\Database\seeds;

use App\Modules\ContentManagement\Models\SitePage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'content' => 'Content of About Us',
                'content_type' => 'about-us'
            ],
            [
                'content' => 'Content of Privacy Policy',
                'content_type' => 'privacy-policy'
            ],
            [
                'content' => 'Content of Terms and Conditions',
                'content_type' => 'terms-and-conditions'
            ],
        ];

        foreach($pages as $page){
            SitePage::updateOrcreate($page);
        }
    }
}
