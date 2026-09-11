<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\BannerSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GameController extends Controller
{
    /**
     * Public: Display main store page with all active games.
     */
    public function index()
    {
        $featuredGames = Game::active()->featured()->take(3)->get();
        $games = Game::active()->latest()->get();

        // Fetch banners for the homepage slider
        $banners = BannerSlider::where('is_active', true)->orderBy('display_order')->get();

        return view('store', compact('games', 'featuredGames', 'banners'));
    }

    /**
     * Public: Display single game details by slug.
     */
    public function show($slug)
    {
        // Find game by slug
        $game = Game::where('slug', $slug)->first();
        
        if (!$game) {
            abort(404, 'Game not found');
        }
        
        if (!$game->is_active) {
            abort(404, 'Game is not active');
        }

        $relatedGames = Game::active()
            ->where('genre', $game->genre)
            ->where('id', '!=', $game->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $banners = $game->getActiveBanners();

        return view('managegame.guest_gamedetails', compact('game', 'relatedGames', 'banners'));
    }

    /**
     * Admin: Paginated games list
     */
    public function adminIndex()
    {
        $games = Game::latest()->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    /**
     * Admin: Non-paginated for AJAX table
     */
    public function adminIndexAlt()
    {
        $games = Game::all();
        return view('managegame.index', compact('games'));
    }

    /**
     * Admin: Store a new game (AJAX)
     */
    public function store(Request $request)
    {
        $validated = $this->validateGame($request);

        $game = Game::create($validated);

        return response()->json(['game' => $game], 201);
    }

    /**
     * Admin: Edit game (AJAX JSON)
     */
    public function edit(Game $game)
    {
        return response()->json(['game' => $game], 200);
    }

    /**
     * Admin: Update game (AJAX)
     */
    public function update(Request $request, Game $game)
    {
        $validated = $this->validateGame($request, $game);

        $game->update($validated);

        return response()->json(['game' => $game], 200);
    }

    /**
     * Admin: Delete game (AJAX)
     */
    public function destroy(Game $game)
    {
        if ($game->image_path) {
            Storage::disk('public')->delete($game->image_path);
        }

        // Delete related banners
        foreach ($game->banners as $banner) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->delete();
        }

        $game->delete();

        return response()->json(['success' => true], 200);
    }

    /**
     * Admin: Validate request data for create/update
     */
    private function validateGame(Request $request, Game $game = null)
    {
        $rules = [
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'release_date'   => 'nullable|date',
            'trailer_url'    => 'nullable|url',
            'tags'           => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'genre'          => 'required|string|max:100',
            'developer'      => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'min_requirements' => 'nullable|string',
            'rec_requirements' => 'nullable|string',
            'is_featured'    => 'sometimes|boolean',
            'is_active'      => 'sometimes|boolean',
            'supports_windows'   => 'sometimes|boolean',
            'supports_controller'=> 'sometimes|boolean',
            'is_single_player'   => 'sometimes|boolean',
        ];

        $validated = $request->validate($rules);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($game && $game->image_path) {
                Storage::disk('public')->delete($game->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('games', 'public');
        }

        // Boolean fields - properly handle checkbox values
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['supports_windows'] = $request->boolean('supports_windows');
        $validated['supports_controller'] = $request->boolean('supports_controller');
        $validated['is_single_player'] = $request->boolean('is_single_player');

        // Slug
        $validated['slug'] = Str::slug($validated['title']);

        return $validated;
    }

    /**
     * Search games dynamically by title or genre (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $games = Game::where('title', 'LIKE', "%$query%")
                    ->orWhere('genre', 'LIKE', "%$query%")
                    ->get();

        return response()->json(['games' => $games], 200);
    }

    /**
     * Toggle active / featured status via AJAX
     */
    public function toggleStatus(Game $game, $field)
    {
        if (!in_array($field, ['is_active', 'is_featured'])) {
            return response()->json(['message' => 'Invalid field'], 400);
        }

        $game->$field = !$game->$field;
        $game->save();

        return response()->json(['game' => $game], 200);
    }

    /**
     * Helper: Fetch all games for alternate views
     */
    public function allGames()
    {
        $games = Game::all();
        return view('games.index', compact('games'));
    }

    // -------------------------
    // --- Banner / Slider CRUD ---
    // -------------------------

    /**
     * Admin: Create banner for a game
     */
    public function storeBanner(Request $request, Game $game)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $banner = $game->banners()->create([
            'image_path' => $path,
            'is_active' => $request->has('is_active')
        ]);

        return response()->json(['banner' => $banner], 201);
    }

    /**
     * Admin: Delete a banner
     */
    public function destroyBanner(BannerSlider $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return response()->json(['success' => true], 200);
    }

    /**
     * Admin: Toggle banner status
     */
    public function toggleBannerStatus(BannerSlider $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return response()->json(['banner' => $banner], 200);
    }

    /**
     * Admin: List banners for a game
     */
    public function bannersForGame(Game $game)
    {
        $banners = $game->banners()->latest()->get();
        return response()->json(['banners' => $banners], 200);
    }
}