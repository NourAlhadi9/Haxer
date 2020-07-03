<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $categories = [
            'Web Exploitation', 
            'Reverse Engineering',
            'Digital Forensics',
            'Cryptography',
            'General Information'
        ];

        foreach ($categories as $category) {
            $cat = new Category();
            $cat->name = $category;
            $cat->save();
        }
    }
}
