<?php

use Illuminate\Database\Seeder;
use App\ProductCategory;

class ProductCategoriesSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'Uniforms',
                'slug' => 'uniforms',
                'icon' => 'shirt',
                'image' => null,
                'is_active' => 1,
                'is_deleted' => 0,
            ],
            [
                'name' => 'Equipment',
                'slug' => 'equipment',
                'icon' => 'soccer-ball-o',
                'image' => null,
                'is_active' => 1,
                'is_deleted' => 0,
            ],
        ];

        foreach ($items as $data) {
            $existing = ProductCategory::where('slug', $data['slug'])->first();
            if (!$existing) {
                $pc = new ProductCategory();
                $pc->name = $data['name'];
                $pc->slug = $data['slug'];
                $pc->icon = $data['icon'];
                $pc->image = $data['image'];
                $pc->is_active = $data['is_active'];
                $pc->is_deleted = $data['is_deleted'];
                $pc->save();
            }
        }
    }
}
