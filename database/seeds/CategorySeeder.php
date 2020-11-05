<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = ['horror', 'comedy', 'php', 'code', 'accountant'];
        foreach($categories as $category){
            DB::table('categories')
                ->insert([
                    'name' => $category,
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now()
            ]);
        }
    }
}
