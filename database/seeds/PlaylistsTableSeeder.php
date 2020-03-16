<?php

use App\Playlist;
use Illuminate\Database\Seeder;

class PlaylistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(Playlist::count()){
            Playlist::truncate();
        }

        $playlists = [
          'Playlist 1',
          'Playlist 2'
        ];

        foreach ($playlists as $playlist){
            Playlist::create([
                'user_id' => 2,
                'title' => $playlist
            ]);
        }

        $this->command->info('Created playlists: ' . implode(',',$playlists));
    }
}
