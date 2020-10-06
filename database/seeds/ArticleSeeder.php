<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i = 1; $i <= 5; $i++):
            DB::table('articles')
                ->insert([
                    'title' => $faker->word,
                    'description' => $faker->text,
                    'id_category' => $faker->numberBetween(1,5),
                    'id_author' => $faker->numberBetween(1,5),
                    'hastag' => $faker->randomElement($array = array ("#covid", "#india", "#student")),
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
            ]);
        endfor;
    }
}
