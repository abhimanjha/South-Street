<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories if they don't exist
        $categories = [
            ['name' => 'Men', 'slug' => 'men'],
            ['name' => 'Women', 'slug' => 'women'],
            ['name' => 'Kids', 'slug' => 'kids'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }

        $products = [
            [
                'name' => 'Classic White T-Shirt',
                'slug' => 'classic-white-t-shirt',
                'sku' => 'TSHIRT-WHT-001',
                'description' => 'A comfortable and versatile white t-shirt perfect for everyday wear.',
                'full_description' => 'Made from high-quality cotton, this classic white t-shirt offers comfort and durability. Ideal for casual outings or layering.',
                'category_id' => 1,
                'price' => 19.99,
                'brand' => 'GarmentCo',
                'stock_quantity' => 50,
                'fabric' => 'Cotton',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Blue Denim Jeans',
                'slug' => 'blue-denim-jeans',
                'sku' => 'JEANS-BLU-001',
                'description' => 'Stylish blue denim jeans with a perfect fit.',
                'full_description' => 'These blue denim jeans are crafted for comfort and style. Perfect for casual wear or office settings.',
                'category_id' => 1,
                'price' => 49.99,
                'brand' => 'DenimWorks',
                'stock_quantity' => 30,
                'fabric' => 'Denim',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Elegant Evening Dress',
                'slug' => 'elegant-evening-dress',
                'sku' => 'DRESS-EVE-001',
                'description' => 'A beautiful evening dress for special occasions.',
                'full_description' => 'This elegant evening dress features intricate detailing and a flattering silhouette. Made from premium fabric.',
                'category_id' => 2,
                'price' => 89.99,
                'brand' => 'Fashionista',
                'stock_quantity' => 20,
                'fabric' => 'Silk',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Kids Cartoon Hoodie',
                'slug' => 'kids-cartoon-hoodie',
                'sku' => 'HOODIE-KID-001',
                'description' => 'Fun and cozy hoodie for kids with cartoon prints.',
                'full_description' => 'Keep your kids warm and stylish with this fun cartoon hoodie. Made from soft, durable material.',
                'category_id' => 3,
                'price' => 29.99,
                'brand' => 'KidWear',
                'stock_quantity' => 40,
                'fabric' => 'Cotton Blend',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Summer Floral Skirt',
                'slug' => 'summer-floral-skirt',
                'sku' => 'SKIRT-FLO-001',
                'description' => 'Light and airy floral skirt perfect for summer.',
                'full_description' => 'This floral skirt brings a touch of spring to your wardrobe. Lightweight and comfortable for warm weather.',
                'category_id' => 2,
                'price' => 34.99,
                'brand' => 'SummerStyle',
                'stock_quantity' => 25,
                'fabric' => 'Chiffon',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Casual Sneakers',
                'slug' => 'casual-sneakers',
                'sku' => 'SNEAKERS-CAS-001',
                'description' => 'Comfortable sneakers for everyday use.',
                'full_description' => 'These casual sneakers offer great comfort and support. Perfect for walking or light activities.',
                'category_id' => 1,
                'price' => 59.99,
                'brand' => 'FootComfort',
                'stock_quantity' => 35,
                'fabric' => 'Synthetic',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create a placeholder image for each product
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'imgs/placeholder.jpg',
                'is_primary' => true,
                'order' => 1,
            ]);
        }
    }
}
