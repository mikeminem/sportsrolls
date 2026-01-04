<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\ProductCategory;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $uniforms = ProductCategory::where('slug', 'uniforms')->first();
        $equipment = ProductCategory::where('slug', 'equipment')->first();

        $items = [];
        if ($uniforms) {
            $items[] = [
                'product_category_id' => $uniforms->id,
                'name' => 'Team Jersey',
                'slug' => 'team-jersey',
                'description' => 'Official team jersey with customizable name and number.',
                'image' => null,
                'price' => 29.99,
                'is_active' => 1,
                'is_deleted' => 0,
            ];
        }
        if ($equipment) {
            $items[] = [
                'product_category_id' => $equipment->id,
                'name' => 'Soccer Ball',
                'slug' => 'soccer-ball',
                'description' => 'Match-quality soccer ball suitable for training and games.',
                'image' => null,
                'price' => 19.99,
                'is_active' => 1,
                'is_deleted' => 0,
            ];
        }

        foreach ($items as $data) {
            $existing = Product::where('slug', $data['slug'])->first();
            if (!$existing) {
                $p = new Product();
                $p->product_category_id = $data['product_category_id'];
                $p->name = $data['name'];
                $p->slug = $data['slug'];
                $p->description = $data['description'];
                $p->image = $data['image'];
                $p->price = $data['price'];
                $p->is_active = $data['is_active'];
                $p->is_deleted = $data['is_deleted'];
                $p->save();
            }
        }
    }
}
