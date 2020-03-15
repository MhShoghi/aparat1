<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(Tag::count()){
            Tag::truncate();
        }
        $tags = [
            'عمومی',
            'خبری',
            'علم و تکنولوژی',
            'ورزشی',
            'بانوان',
            'بازی',
            'طنز',
            'آموزشی',
            'تفریحی',
            'فیلم',
            'مذهبی',
            'موسیقی',
            'سیاسی',
            'حوادث',
            'گردشگری',
            'حیوانات',
            'متفرقه',
            'تبلیغات',
            'هنری',
            'کارتون',
            'سلامت',
            'آپارات و کودک'
        ];

        foreach ($tags as $index => $tagName){
            Tag::create([
                'title' => $tagName
            ]);

            $this->command->info('Add '. $index .' tag');
        }
    }
}
