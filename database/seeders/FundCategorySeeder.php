<?php

namespace Database\Seeders;

use App\Models\FundCategory;
use Illuminate\Database\Seeder;

class FundCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Operational Fund',
                'slug' => 'operational',
                'description' => 'For temple operational expenses like electricity, water, and maintenance',
                'icon' => 'fa-tools',
                'color' => '#2A9D8F',
            ],
            [
                'name' => 'Social & Charity',
                'slug' => 'social',
                'description' => 'For humanitarian acts and charity activities',
                'icon' => 'fa-hands-helping',
                'color' => '#D62828',
            ],
            [
                'name' => 'Infrastructure',
                'slug' => 'infrastructure',
                'description' => 'For building development and facility improvements',
                'icon' => 'fa-building',
                'color' => '#F4A261',
            ],
            [
                'name' => 'Ritual & Ceremony',
                'slug' => 'ritual',
                'description' => 'For ritual materials and special ceremonies',
                'icon' => 'fa-pray',
                'color' => '#E8B923',
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'For dhamma classes and educational programs',
                'icon' => 'fa-book',
                'color' => '#457B9D',
            ],
        ];

        foreach ($categories as $category) {
            FundCategory::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
