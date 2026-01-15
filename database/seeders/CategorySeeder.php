<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create main categories (using updateOrCreate to avoid duplicates)
        $men = Category::updateOrCreate(
            ['slug' => 'men'],
            [
            'name' => 'Men',
            'description' => 'Men clothing and accessories',
            'is_active' => true,
            'order' => 1
            ]
        );

        $women = Category::updateOrCreate(
            ['slug' => 'women'],
            [
            'name' => 'Women',
            'description' => 'Women clothing and accessories',
            'is_active' => true,
            'order' => 2
            ]
        );

        $kids = Category::updateOrCreate(
            ['slug' => 'kids'],
            [
            'name' => 'Kids',
            'description' => 'Kids clothing and accessories',
            'is_active' => true,
            'order' => 3
            ]
        );

        // Create subcategories for Men
        Category::updateOrCreate(
            ['slug' => 'men-shirts'],
            [
            'name' => 'Shirts',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 1
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'men-pants'],
            [
            'name' => 'Pants',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 2
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'men-tshirts'],
            [
            'name' => 'T-Shirts',
            'parent_id' => $men->id,
            'is_active' => true,
            'order' => 3
            ]
        );

        // Create subcategories for Women
        Category::updateOrCreate(
            ['slug' => 'women-dresses'],
            [
            'name' => 'Dresses',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 1
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'women-tops'],
            [
            'name' => 'Tops',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 2
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'women-skirts'],
            [
            'name' => 'Skirts',
            'parent_id' => $women->id,
            'is_active' => true,
            'order' => 3
            ]
        );

        // Create subcategories for Kids
        Category::updateOrCreate(
            ['slug' => 'kids-boys'],
            [
            'name' => 'Boys',
            'parent_id' => $kids->id,
            'is_active' => true,
            'order' => 1
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'kids-girls'],
            [
            'name' => 'Girls',
            'parent_id' => $kids->id,
            'is_active' => true,
            'order' => 2
            ]
        );
    }
}