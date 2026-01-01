<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create main categories
        $men = Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men clothing and accessories',
            'is_active' => true,
            'order' => 1
        ]);

        $women = Category::create([
            'name' => 'Women',
            'slug' => 'women',
            'description' => 'Women clothing and accessories',
            'is_active' => true,
            'order' => 2
        ]);

        $kids = Category::create([
            'name' => 'Kids',
            'slug' => 'kids',
            'description' => 'Kids clothing and accessories',
            'is_active' => true,
            'order' => 3
        ]);

        // Create subcategories for Men
        Category::create([
            'name' => 'Shirts',
            'slug' => 'men-shirts',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Pants',
            'slug' => 'men-pants',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 2
        ]);

        Category::create([
            'name' => 'T-Shirts',
            'slug' => 'men-tshirts',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 3
        ]);

        // Create subcategories for Women
        Category::create([
            'name' => 'Dresses',
            'slug' => 'women-dresses',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Tops',
            'slug' => 'women-tops',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 2
        ]);

        Category::create([
            'name' => 'Skirts',
            'slug' => 'women-skirts',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 3
        ]);

        // Create subcategories for Kids
        Category::create([
            'name' => 'Boys',
            'slug' => 'kids-boys',
            'parent_id' => $kids->id,
            'is_active' => true,
            'order' => 1
        ]);

        Category::create([
            'name' => 'Girls',
            'slug' => 'kids-girls',
            'parent_id' => $kids->id,
            'is_active' => true,
            'order' => 2
        ]);
    }
}