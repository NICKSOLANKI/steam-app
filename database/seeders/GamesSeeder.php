<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\BannerSlider;

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset current data as requested
        DB::table('banner_sliders')->truncate();
        DB::table('games')->delete();

        $seed = [
            // Banner slider assets referenced in snippet
            ['Wolverine','wolverine','Action','Insomniac Games','counter.jpg',2299,4599,true],
            ['The Last of Us Part II','the-last-of-us-part-ii','Adventure','Naughty Dog','last.jpg',1899,3999,true],
            ['Spider-Man: Miles Morales','spider-man-miles-morales','Action','Insomniac Games','s8ul.jpg',1799,4199,true],

            // Grid games from snippet
            ['God of War','god-of-war','Action','Santa Monica Studio','god.webp',1899,3999,false],
            ['Marvel Wolverine','marvel-wolverine','Action','Insomniac Games','Wolverin.webp',1599,2999,false],
            ['GTA V','gta-v','Racing','Rockstar Games','gtav.jpg',1299,2499,false],
            ["Assassin's Creed Valhalla",'assassins-creed-valhalla','Action','Ubisoft','banner-image.jpg',2199,4299,false],
            ['Ghost of Tsushima','ghost-of-tsushima','Adventure','Sucker Punch Productions','ghost.avif',2399,4999,false],
            ['Tekken 8','tekken-8','Adventure','Bandai Namco','sa.webp',1499,2999,false],
            ['Hogwarts Legacy','hogwarts-legacy','Adventure','Avalanche','hog.webp',1799,3499,false],
            ['Cyberpunk 2077','cyberpunk-2077','RPG','CD Projekt Red','cyber2.jpg',1999,3999,false],
            ['Red Dead Redemption 2','red-dead-redemption-2','RPG','Rockstar Games','ws.webp',1299,2499,false],
            ['Elden Ring','elden-ring','RPG','FromSoftware','mp.webp',2999,4999,false],
            ['Alan Wake 2','alan-wake-2','RPG','Remedy','alan.webp',2499,4299,false],
            ['Counter Strike','counter-strike','Shooter','Valve','count.jpg',999,1999,false],
            ['VALORANT','valorant','Shooter','Riot Games','val.webp',0,2499,false],
            ['Death Stranding','death-stranding','Shooter','Kojima Productions','sd.webp',3499,4999,false],
            ['Starfield','starfield','Shooter','Bethesda','sm.webp',1799,3499,false],
            ['Forza Horizon 5','forza-horizon-5','Racing','Playground Games','forza5.jpg',1799,3499,false],
            ['FIFA 18','fifa-18','Action','EA','fifaa.webp',1499,2999,false],
            ['Call of Duty Warzone 2','call-of-duty-warzone-2','Action','Activision','single-game.jpg',0,1999,false],
            ['Apex Legends','apex-legends','Shooter','Respawn','cm.webp',0,2499,false],
            ['Spider-Man Miles Morales','spider-man-miles','Shooter','Insomniac Games','spi.jpg',0,2999,false],
            ['Gran Turismo 7','gran-turismo-7','Racing','Polyphony Digital','categories-01.jpg',3499,4999,false],
            ['F1 2023','f1-2023','Action','EA','sea.jpg',2499,3999,false],
        ];

        $created = [];
        foreach ($seed as [$title,$slug,$genre,$developer,$assetFile,$price,$old,$featured]) {
            $imagePath = $this->copyAssetToStorage($assetFile);
            $game = Game::create([
                'title' => $title,
                'slug' => $slug,
                'description' => $this->defaultDescriptionFor($title, $genre),
                'price' => $price,
                'original_price' => $old,
                'genre' => $genre,
                'developer' => $developer,
                'image_path' => $imagePath,
                'is_featured' => (bool)$featured,
                'is_active' => true,
            ]);
            $created[$slug] = $game;
        }

        // Set the 3 banner sliders to match the first section
        $sliders = [
            ['slug' => 'wolverine', 'file' => 'counter.jpg', 'old' => 4599, 'new' => 2299, 'order' => 1],
            ['slug' => 'the-last-of-us-part-ii', 'file' => 'last.jpg', 'old' => 3999, 'new' => 1899, 'order' => 2],
            ['slug' => 'spider-man-miles-morales', 'file' => 's8ul.jpg', 'old' => 4199, 'new' => 1799, 'order' => 3],
        ];

        foreach ($sliders as $s) {
            $imgPath = $this->copyAssetToStorage($s['file']) ?? null;
            $game = $created[$s['slug']] ?? Game::where('slug', $s['slug'])->first();
            BannerSlider::updateOrCreate(
                ['display_order' => $s['order']],
                [
                    'game_id' => $game?->id,
                    'title' => $game?->title ?? Str::headline($s['slug']),
                    'slug' => $s['slug'],
                    'image_path' => $imgPath,
                    'price' => $s['new'],
                    'original_price' => $s['old'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function copyAssetToStorage(string $filename): ?string
    {
        $source = public_path('asset/imagies/'.$filename);
        if (!file_exists($source)) {
            return null;
        }
        $ext = pathinfo($source, PATHINFO_EXTENSION) ?: 'jpg';
        $name = Str::random(40).'.'.$ext;
        $target = 'games/'.$name;
        $data = file_get_contents($source);
        Storage::disk('public')->put($target, $data);
        return $target;
    }

    private function defaultDescriptionFor(string $title, string $genre): string
    {
        return $title.' is a '.$genre.' game available in our store.';
    }
}