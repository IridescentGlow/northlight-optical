<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Uses the query builder with insertOrIgnore so it works on MySQL and
     * SQLite alike, and is safe to run on every boot: rows whose unique
     * keys (id, name, slug) already exist are skipped instead of failing.
     */
    public function run(): void
    {
        $now = now();

        DB::table('products')->insertOrIgnore([
            [
                'id' => 1,
                'name' => 'Classic Black Sunglasses',
                'slug' => 'classic-black-sunglasses',
                'short_description' => 'Timeless black frame suitable for any occasion.',
                'long_description' => 'Enhance your style with these classic black sunglasses. The timeless black frame makes them suitable for any occasion, adding a touch of sophistication to your look. Whether you\'re heading to a casual outing or a formal event, these sunglasses are a versatile accessory. Protect your eyes in style!',
                'category' => 'sunglasses',
                'price' => 49.99,
                'image_url' => 'images/sunglasses1.jpg',
                'is_featured' => 1,
                'on_sale' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Fashion Aviator Sunglasses',
                'slug' => 'fashion-aviator-sunglasses',
                'short_description' => 'Stylish aviator sunglasses with UV protection.',
                'long_description' => 'Make a fashion statement with these stylish aviator sunglasses. The sleek design and UV protection make them a must-have accessory. Whether you\'re strolling down the street or lounging by the pool, these sunglasses will elevate your look while providing essential eye protection.',
                'category' => 'sunglasses',
                'price' => 69.99,
                'image_url' => 'images/sunglasses4.jpg',
                'is_featured' => 1,
                'on_sale' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Sporty Blue Light Glasses',
                'slug' => 'sporty-blue-light-glasses',
                'short_description' => 'Protect your eyes from digital eye strain with these sporty blue light glasses.',
                'long_description' => 'Stay comfortable during long hours of screen time with these sporty blue light glasses. Designed to protect your eyes from digital eye strain, these glasses are ideal for work or leisure. The sporty design adds a touch of flair to your eyewear collection.',
                'category' => 'sunglasses',
                'price' => 29.99,
                'image_url' => 'images/sunglasses3.jpg',
                'is_featured' => 0,
                'on_sale' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Vintage Round Eyeglasses',
                'slug' => 'vintage-round-eyeglasses',
                'short_description' => 'Classic round eyeglasses for a retro look.',
                'long_description' => 'Achieve a retro-inspired look with these vintage round eyeglasses. The classic design adds a touch of nostalgia to your style, making them a perfect choice for those who appreciate timeless fashion. These eyeglasses combine fashion and function for a stylish and clear-eyed experience.',
                'category' => 'eyeglasses',
                'price' => 59.99,
                'image_url' => 'images/eyeglasses5.jpg',
                'is_featured' => 0,
                'on_sale' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'name' => 'Designer Octave Glasses Sunglasses',
                'slug' => 'designer-octave-sunglasses',
                'short_description' => 'Elegant cat-eye sunglasses designed for a chic appearance.',
                'long_description' => 'Step into the world of elegance with these designer octave glasses sunglasses. The cat-eye design exudes sophistication, making them a perfect accessory for any fashion-forward individual. Elevate your style and protect your eyes with these chic sunglasses.',
                'category' => 'eyeglasses',
                'price' => 79.99,
                'image_url' => 'images/eyeglasses4.jpg',
                'is_featured' => 1,
                'on_sale' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'name' => 'Square Matte Black Glasses',
                'slug' => 'square-matte-black-glasses',
                'short_description' => 'Fun and colorful glasses featuring popular cartoon characters for kids.',
                'long_description' => 'Add a playful touch to your child\'s eyewear collection with these square matte black glasses. Featuring popular cartoon characters, these glasses make wearing eyewear fun for kids. The matte black frame adds a touch of coolness to their look.',
                'category' => 'sunglasses',
                'price' => 39.99,
                'image_url' => 'images/sunglasses2.jpg',
                'is_featured' => 1,
                'on_sale' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'name' => 'Coexist Outdoor Glasses',
                'slug' => 'coexist-outdoor-glasses',
                'short_description' => 'Ideal for outdoor activities with polarized lenses for glare reduction.',
                'long_description' => 'Embrace outdoor activities with confidence wearing these Coexist outdoor glasses. The polarized lenses reduce glare, providing clear vision in bright conditions. Whether you\'re hiking, biking, or simply enjoying nature, these glasses offer both style and functionality for your outdoor adventures.',
                'category' => 'eyeglasses',
                'price' => 89.99,
                'image_url' => 'images/eyeglasses3.jpg',
                'is_featured' => 1,
                'on_sale' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'name' => 'Minimalist Metal Frame Glasses',
                'slug' => 'minimalist-metal-frame-glasses',
                'short_description' => 'Sleek and minimalist glasses with a durable metal frame.',
                'long_description' => 'Achieve a sleek and minimalist look with these glasses featuring a durable metal frame. The minimalist design adds a touch of sophistication to your style, making these glasses a versatile accessory for various occasions. Elevate your eyewear collection with these modern and stylish frames.',
                'category' => 'eyeglasses',
                'price' => 54.99,
                'image_url' => 'images/eyeglasses1.jpg',
                'is_featured' => 0,
                'on_sale' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
