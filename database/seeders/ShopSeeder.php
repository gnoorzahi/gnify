<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronic devices and accessories'
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'slug' => 'clothing',
            'description' => 'Fashion and apparel'
        ]);

        $books = Category::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Books and educational materials'
        ]);

        $home = Category::create([
            'name' => 'Home & Garden',
            'slug' => 'home-garden',
            'description' => 'Home decor and garden supplies'
        ]);

        // Create products
        $products = [
            [
                'name' => 'Smartphone X Pro',
                'description' => 'Latest smartphone with advanced features',
                'price' => 999.99,
                'category_id' => $electronics->id,
                'stock_quantity' => 50,
                'sku' => 'PHONE001'
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Premium noise-cancelling wireless headphones',
                'price' => 299.99,
                'category_id' => $electronics->id,
                'stock_quantity' => 100,
                'sku' => 'HEAD001'
            ],
            [
                'name' => 'Laptop Pro 15"',
                'description' => 'High-performance laptop for professionals',
                'price' => 1499.99,
                'category_id' => $electronics->id,
                'stock_quantity' => 30,
                'sku' => 'LAPTOP001'
            ],
            [
                'name' => 'Cotton T-Shirt',
                'description' => 'Comfortable 100% cotton t-shirt',
                'price' => 29.99,
                'category_id' => $clothing->id,
                'stock_quantity' => 200,
                'sku' => 'TSHIRT001'
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic fit denim jeans',
                'price' => 79.99,
                'category_id' => $clothing->id,
                'stock_quantity' => 150,
                'sku' => 'JEANS001'
            ],
            [
                'name' => 'Winter Jacket',
                'description' => 'Warm and stylish winter jacket',
                'price' => 149.99,
                'category_id' => $clothing->id,
                'stock_quantity' => 75,
                'sku' => 'JACKET001'
            ],
            [
                'name' => 'Programming Guide',
                'description' => 'Complete guide to modern programming',
                'price' => 49.99,
                'category_id' => $books->id,
                'stock_quantity' => 100,
                'sku' => 'BOOK001'
            ],
            [
                'name' => 'Science Fiction Novel',
                'description' => 'Bestselling science fiction adventure',
                'price' => 24.99,
                'category_id' => $books->id,
                'stock_quantity' => 80,
                'sku' => 'BOOK002'
            ],
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with timer',
                'price' => 89.99,
                'category_id' => $home->id,
                'stock_quantity' => 60,
                'sku' => 'HOME001'
            ],
            [
                'name' => 'Indoor Plant Set',
                'description' => 'Set of 3 easy-care indoor plants',
                'price' => 39.99,
                'category_id' => $home->id,
                'stock_quantity' => 40,
                'sku' => 'PLANT001'
            ],
        ];

        foreach ($products as $productData) {
            $productData['slug'] = Str::slug($productData['name']);
            $productData['images'] = [
                'https://via.placeholder.com/400x300.png?text=' . urlencode($productData['name'])
            ];
            Product::create($productData);
        }
    }
}
