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

        if (\App\User::count()) {
            \App\User::truncate();
            \App\Channel::truncate();
        }

        $this->createAdminUser();

        for ($i = 1; $i < 5; $i++) {
            $this->createUser($i);
        }
    }

    private function createUser($num = 1)
    {

        $user = factory(\App\User::class)->make([
            'type' => \App\User::TYPE_USER,
            'name' => 'User ' . $num,
            'email' => 'user' . $num . '@aparat.me',
            'mobile' => '+989' . str_repeat($num, 9),
        ]);

        $user->save();

        $this->command->info('Create User '. $num . ' with mobile: '.$user->mobile);

    }

    private function createAdminUser()
    {
        $user = factory(\App\User::class)->make([
            'type' => \App\User::TYPE_ADMIN,
            'name' => 'Administrator',
            'email' => 'admin@aparat.me',
            'mobile' => '+989000000000'
        ]);

        $user->save();

        $this->command->info('Create Admin User');
    }
}
