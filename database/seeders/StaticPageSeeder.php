<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staticPage = [
            [
                'name' => 'About Us',
                "slug" => 'about-us',
                'meta_title' => 'About Us',
                'meta_description' => '',
                'content' => '<h1>About Us</h1><br/><p></p>'
            ],
            [
                'name' => 'Career',
                "slug" => 'career',
                'meta_title' => 'Career',
                'meta_description' => '',
                'content' => '<h1>Career</h1><br/><p></p>'
            ]
        ];

        foreach ($staticPage as $page) {
            StaticPage::updateOrInsert(
                ['name' => $page['name']],
                $page
            );
        }
    }
}
