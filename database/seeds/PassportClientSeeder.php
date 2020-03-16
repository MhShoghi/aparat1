<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createPersonalClient();
        $this->createPasswordClient();


    }

    public function createPersonalClient(): void
    {
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Personal Access Client',
            'redirect' => 'http://localhost',
            'secret' => 'sfwHRrVJVHaH4gtWBGIcuJO8WQh8pdwvoZimrUrW',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Personal client created!');
    }

    private function createPasswordClient()
    {
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Password Access Client',
            'redirect' => 'http://localhost',
            'secret' => 'V78Pf5Xi8BtYwsXwwxFQ1lLlXyOJg4fAWyKLVFR5',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Password client created!');

    }
}
