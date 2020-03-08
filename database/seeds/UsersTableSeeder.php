<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminUser();

        $this->createUser();
    }

    private function createUser()
    {

        $user = factory(\App\User::class)->make([
            'type' => \App\User::TYPE_USER,
            'email' => 'user1@aparat.me',
            'mobile' => '+989222222222',
        ]);

        $user->save();

        $this->command->info('Create Default User');

    }

    private function createAdminUser()
    {
        $user = factory(\App\User::class)->make([
            'type' => \App\User::TYPE_ADMIN,
            'name' => 'Administrator',
            'email' => 'admin@aparat.me',
            'mobile' => '+989111111111'
        ]);

        $user->save();

        $this->command->info('Create Admin User');
    }
}
