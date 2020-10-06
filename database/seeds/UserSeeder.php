<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
                ->insert([
                    'name' => 'admin',
                    'email' => 'admin@eifil-indonesia.org',
                    'password' => '$2y$10$UdUZyJsjdAsQGoP4kmImbeCyk594J75n/Vsi6PVEJ8gdENihp7w.W', //admin123
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
            ]);
    }
}
