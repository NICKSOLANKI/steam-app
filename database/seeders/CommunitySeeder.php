<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CommunityServer;
use App\Models\CommunityChannel;
use App\Models\CommunityMember;
use App\Models\User;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get a user to be the server owner
        $user = User::where('email', 'dhaval@gmail.com')->first();
        
        if (!$user) {
            $user = User::first();
        }
        
        if (!$user) {
            $this->command->warn('No users found. Please create a user first.');
            return;
        }

        if (CommunityServer::count() > 0) {
            $this->command->info('Community servers already exist, skipping seed.');
            return;
        }

        // Create sample servers
        $servers = [
            [
                'name' => 'Gaming Hub',
                'description' => 'A place for gamers to connect and chat',
                'is_public' => true,
                'channels' => [
                    ['name' => 'general', 'type' => 'text'],
                    ['name' => 'game-discussion', 'type' => 'text'],
                    ['name' => 'General Voice', 'type' => 'voice'],
                    ['name' => 'Gaming Voice', 'type' => 'voice'],
                ]
            ],
            [
                'name' => 'Steam Community',
                'description' => 'Official STEAM community server',
                'is_public' => true,
                'channels' => [
                    ['name' => 'welcome', 'type' => 'text'],
                    ['name' => 'announcements', 'type' => 'text'],
                    ['name' => 'General', 'type' => 'voice'],
                ]
            ],
            [
                'name' => 'Tech Talk',
                'description' => 'Discuss technology and gaming',
                'is_public' => true,
                'channels' => [
                    ['name' => 'tech-news', 'type' => 'text'],
                    ['name' => 'help', 'type' => 'text'],
                    ['name' => 'Tech Voice', 'type' => 'voice'],
                ]
            ]
        ];

        foreach ($servers as $serverData) {
            $channels = $serverData['channels'];
            unset($serverData['channels']);

            $server = CommunityServer::create(array_merge($serverData, [
                'owner_id' => $user->id,
            ]));

            // Add owner as member
            CommunityMember::create([
                'server_id' => $server->id,
                'user_id' => $user->id,
                'role' => 'owner',
                'joined_at' => now(),
            ]);

            // Add all other users as members
            $otherUsers = User::where('id', '!=', $user->id)->get();
            foreach ($otherUsers as $otherUser) {
                CommunityMember::create([
                    'server_id' => $server->id,
                    'user_id' => $otherUser->id,
                    'role' => 'member',
                    'joined_at' => now(),
                ]);
            }

            // Create channels
            foreach ($channels as $index => $channelData) {
                CommunityChannel::create([
                    'server_id' => $server->id,
                    'name' => $channelData['name'],
                    'type' => $channelData['type'],
                    'position' => $index + 1,
                ]);
            }
        }

        $this->command->info('Community servers seeded successfully!');
    }
}
