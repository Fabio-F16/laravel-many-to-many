<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $categories = ['Spa', 'Trattamenti', 'Cibo', 'Wellness'];

        foreach ($categories as $item) {
            $new_category_object = new Category();
            $new_category_object->name = $item;
            $new_category_object->slug = Str::slug($item);
            $new_category_object->save();
        }
    }
}
