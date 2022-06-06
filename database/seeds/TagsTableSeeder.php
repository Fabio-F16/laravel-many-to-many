<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Tag;
class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tags = ['Routine', 'Mattina', 'Pomeriggio', 'Sera'];

        foreach ($tags as $item) {
            $newTag = new Tag();
            $newTag->name = $item;
            $newTag->slug = Str::slug($item);
            $newTag->save();
        }
    }
}
