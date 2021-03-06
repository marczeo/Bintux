<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RolesTableSeeder::class);
         $this->call(NodesTableSeeder::class);
         $this->call(CicloviasTableSeeder::class);
         $this->call(RoutesTableSeeder::class);
         $this->call(UsersTableSeeder::class);
    }
}
