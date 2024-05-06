<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       
        $this->call(PermissionTableSeeder::class);
        $this->call(RolePermissionNewSeeder::class);
        $this->call(CounterSeeder::class);
        $this->call(RequiredFieldsTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(WalletTableSeeder::class);
        $this->call(CreateAdminUserSeeder::class);



    }
}
