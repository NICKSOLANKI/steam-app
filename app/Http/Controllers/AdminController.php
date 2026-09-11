<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\User;
use App\Models\Admin;
use App\Models\Game;
use App\Models\BannerSlider;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // -----------------------------
    // Dashboard
    // -----------------------------
    public function dashboard()
    {
        $users = User::orderBy('email')->get();
        $totalUsers = $users->count();
        $verifiedUsers = $users->whereNotNull('email_verified_at')->count();

        return view('ADMIN.dashboard', compact('users', 'totalUsers', 'verifiedUsers'));
    }

    // -----------------------------
    // Library
    // -----------------------------
    public function library()
    {
        return view('ADMIN.lb');
    }

    // -----------------------------
    // Products
    // -----------------------------
    public function products()
    {
        // --- MODIFICATION START ---
        // This fetches all the games from the database so they can be
        // displayed on the 'pr.blade.php' page. This fixes the error.
        $games = Game::latest()->get();
        $bannerSliders = BannerSlider::ordered()->with('game')->get();
        return view('ADMIN.pr', compact('games', 'bannerSliders'));
        // --- MODIFICATION END ---
    }

    // -----------------------------
    // Subscriptions
    // -----------------------------
    public function subscriptions()
    {
        $subscriptions = Subscription::with('user')->latest()->get();
        $subscriptionPlans = SubscriptionPlan::ordered()->get();
        
        return view('ADMIN.sub', compact('subscriptions', 'subscriptionPlans'));
    }

    // -----------------------------
    // Subscription Plans Management
    // -----------------------------
    public function storeSubscriptionPlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'price', 'duration_days']);
        $data['is_active'] = $request->boolean('is_active', true);
        
        // Process features
        if ($request->features) {
            $data['features'] = array_filter(explode("\n", $request->features));
        }

        SubscriptionPlan::create($data);

        return redirect()->back()->with('success', 'Subscription plan created successfully!');
    }

    public function updateSubscriptionPlan(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug,' . $subscriptionPlan->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'features' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'slug', 'description', 'price', 'duration_days']);
        $data['is_active'] = $request->boolean('is_active', true);
        
        // Process features
        if ($request->features) {
            $data['features'] = array_filter(explode("\n", $request->features));
        }

        $subscriptionPlan->update($data);

        return redirect()->back()->with('success', 'Subscription plan updated successfully!');
    }

    public function destroySubscriptionPlan(SubscriptionPlan $subscriptionPlan)
    {
        // Check if any active subscriptions use this plan
        $activeSubscriptions = Subscription::where('plan', $subscriptionPlan->slug)
                                          ->where('status', 'active')
                                          ->count();
        
        if ($activeSubscriptions > 0) {
            return redirect()->back()->with('error', 'Cannot delete plan with active subscriptions!');
        }

        $subscriptionPlan->delete();
        return redirect()->back()->with('success', 'Subscription plan deleted successfully!');
    }

    // -----------------------------
    // Settings
    // -----------------------------
    public function settings()
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Please login as admin');
        }

        $admin = Admin::findOrFail($adminId);
        
        // Get system information
        $systemInfo = [
            'total_users' => User::count(),
            'total_games' => Game::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_revenue' => Subscription::where('status', 'active')->sum('price')
        ];

        return view('ADMIN.settings', compact('admin', 'systemInfo'));
    }

    // -----------------------------
    // Admin Profile Update
    // -----------------------------
    public function updateProfile(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Please login as admin');
        }

        $admin = Admin::findOrFail($adminId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'email', 'phone', 'bio']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($admin->avatar) {
                Storage::disk('public')->delete($admin->avatar);
            }
            
            $data['avatar'] = $request->file('avatar')->store('admin-avatars', 'public');
        }

        $admin->update($data);

        // Update session data
        session(['admin_name' => $admin->name, 'admin_email' => $admin->email]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // -----------------------------
    // Change Admin Password
    // -----------------------------
    public function changePassword(Request $request)
    {
        $adminId = session('admin_id');
        if (!$adminId) {
            return redirect()->route('login')->with('error', 'Please login as admin');
        }

        $admin = Admin::findOrFail($adminId);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed'
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        $admin->update(['password' => $request->new_password]);

        return redirect()->back()->with('success', 'Password changed successfully!');
    }

    // -----------------------------
    // Banner Slider Management
    // -----------------------------
    public function updateBannerSlider(HttpRequest $request, BannerSlider $slider)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id'
        ]);

        $game = Game::findOrFail($request->game_id);
        $slider->updateFromGame($game);

        return response()->json([
            'success' => true,
            'message' => 'Banner slider updated successfully'
        ]);
    }

    // Create or update a slide by display order
    public function storeBannerSlider(HttpRequest $request)
    {
        // Check maximum limit for new sliders
        $currentCount = BannerSlider::count();
        $isUpdate = $request->has('_method') && $request->input('_method') === 'PUT';
        
        if (!$isUpdate && $currentCount >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maximum 3 slider banners allowed! Please delete an existing banner first.'
            ], 422);
        }
        
        $data = $request->validate([
            'display_order' => 'required|integer|min:1|max:3',
            'game_id' => 'nullable|exists:games,id',
            'title' => 'required_without:game_id|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required_without:game_id|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'image' => 'required_without:game_id|image|mimes:jpeg,png,jpg,webp|max:4096',
            'is_active' => 'sometimes|boolean',
        ]);
        
        // Check if position is already taken (for new sliders)
        if (!$isUpdate) {
            $existingSlider = BannerSlider::where('display_order', $data['display_order'])->first();
            if ($existingSlider) {
                return response()->json([
                    'success' => false,
                    'message' => 'Position ' . $data['display_order'] . ' is already taken!'
                ], 422);
            }
        }

        try {
            $slider = BannerSlider::firstOrNew(['display_order' => $data['display_order']]);

            if (!empty($data['game_id'])) {
                $game = Game::findOrFail($data['game_id']);
                $slider->game_id = $game->id;
                $slider->updateFromGame($game);
            } else {
                // For custom banners without game_id, set it to null and use custom data
                $slider->game_id = null;
                $slider->title = $data['title'] ?? $slider->title ?? 'Custom Banner';
                $slider->slug = $data['slug'] ?? $slider->slug ?? 'custom-banner-' . $data['display_order'];
                $slider->price = $data['price'] ?? $slider->price ?? 0;
                $slider->original_price = $data['original_price'] ?? $slider->original_price ?? null;
            }

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($slider->image_path) {
                    Storage::disk('public')->delete($slider->image_path);
                }
                $slider->image_path = $request->file('image')->store('banners', 'public');
            }

            $slider->is_active = $request->boolean('is_active', true);
            $slider->display_order = (int)$data['display_order'];
            $slider->save();

            return response()->json([
                'success' => true, 
                'message' => $isUpdate ? 'Banner slider updated successfully!' : 'New banner slider added successfully!',
                'slider' => $slider
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save banner slider: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyBannerSlider(BannerSlider $slider)
    {
        try {
            // Delete associated image if exists
            if ($slider->image_path) {
                Storage::disk('public')->delete($slider->image_path);
            }
            
            $slider->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Banner slider deleted successfully! You can now add a new banner.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete banner slider: ' . $e->getMessage()
            ], 500);
        }
    }

    // -----------------------------
    // Save / Update User
    // -----------------------------
    public function saveUser(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:6'
        ]);

        if (!empty($data['id'])) {
            // Update existing user
            $user = User::find($data['id']);
            $user->email = $data['email'];

            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
                $user->temp_password = $data['password']; // Store for admin viewing
            }

            $user->save();
        } else {
            // Create new user
            $password = $data['password'] ?? 'password123';
            User::create([
                'email' => $data['email'],
                'password' => Hash::make($password),
                'temp_password' => $password, // Store for admin viewing
                'name' => explode('@', $data['email'])[0],
                'role' => 'user'
            ]);
        }

        return redirect()->back()->with('success', 'User saved!');
    }

    // -----------------------------
    // Delete User
    // -----------------------------
    public function deleteUser($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User deleted!');
    }
}