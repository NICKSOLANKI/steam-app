<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;

class SubscriptionController extends Controller
{
    // ---------------------------
    // User Methods
    // ---------------------------

    // Show current user's subscription
    public function index()
    {
        try {
            $userSubscription = null;
            $availablePlans = collect();

            if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions')) {
                $userSubscription = Subscription::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->first();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('subscription_plans')) {
                $availablePlans = SubscriptionPlan::active()->ordered()->get();
            }

            return view('managegame.subscription', compact('userSubscription', 'availablePlans'));
        } catch (\Throwable $e) {
            \Log::error('Subscription page error: ' . $e->getMessage());
            return view('managegame.subscription', [
                'userSubscription' => null,
                'availablePlans' => collect()
            ]);
        }
    }

    // Purchase new subscription
    public function purchase(Request $request)
    {
        try {
            $user = Auth::user();

            // Check for existing active subscription
            $activeSub = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions')) {
                $activeSub = Subscription::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();
            }

            if ($activeSub) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active subscription.'
                ]);
            }

            $planSlug = $request->plan ?? 'monthly';

            // Get the subscription plan details
            $subscriptionPlan = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('subscription_plans')) {
                $subscriptionPlan = SubscriptionPlan::where('slug', $planSlug)->where('is_active', true)->first();
            }

            if (!$subscriptionPlan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid subscription plan selected.'
                ]);
            }

            $startDate = now();
            $endDate = $subscriptionPlan->isLifetime() ? null : $startDate->copy()->addDays($subscriptionPlan->duration_days);

            $subscription = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions')) {
                $subscription = Subscription::create([
                    'user_id'     => $user->id,
                    'plan'        => $subscriptionPlan->slug,
                    'status'      => 'active',
                    'description' => $subscriptionPlan->description,
                    'price'       => $subscriptionPlan->price,
                    'start_date'  => $startDate,
                    'end_date'    => $endDate
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Subscription activated successfully!',
                'subscription' => $subscription
            ]);
        } catch (\Throwable $e) {
            \Log::error('Subscription purchase error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process subscription. Please try again.'
            ]);
        }
    }

    // Open subscription content
    public function open()
    {
        try {
            $subscription = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions')) {
                $subscription = Subscription::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->first();
            }

            if (!$subscription || ($subscription->end_date && $subscription->end_date < now())) {
                return redirect()->route('subscription.index')
                    ->with('error', 'Subscription expired or inactive.');
            }

            return view('managegame.subscription-open');
        } catch (\Throwable $e) {
            \Log::error('Subscription open error: ' . $e->getMessage());
            return redirect()->route('subscription.index')
                ->with('error', 'Unable to access subscription content.');
        }
    }

    // ---------------------------
    // Admin Methods
    // ---------------------------

    // Admin view: list all subscriptions
    public function adminIndex()
    {
        try {
            $subscriptions = collect();
            $subscription = null;

            if (\Illuminate\Support\Facades\Schema::hasTable('subscriptions')) {
                $subscriptions = Subscription::with('user')->get();
                $subscription = $subscriptions->first();
            }

            return view('ADMIN.sub', compact('subscriptions', 'subscription'));
        } catch (\Throwable $e) {
            \Log::error('Admin subscription page error: ' . $e->getMessage());
            return view('ADMIN.sub', [
                'subscriptions' => collect(),
                'subscription' => null
            ]);
        }
    }

    // Update subscription (normal form submission)
    public function update(Request $request, Subscription $subscription)
    {
        try {
            $subscription->update([
                'plan'        => $request->plan,
                'status'      => $request->status,
                'start_date'  => $request->start_date,
                'end_date'    => $request->plan === 'monthly' ? $request->end_date : null,
                'price'       => $request->price ?? $subscription->price,
                'description' => $request->description ?? $subscription->description
            ]);

            return redirect()->route('admin.subscriptions')
                ->with('success', 'Subscription updated!');
        } catch (\Throwable $e) {
            \Log::error('Subscription update error: ' . $e->getMessage());
            return redirect()->route('admin.subscriptions')
                ->with('error', 'Failed to update subscription. Please try again.');
        }
    }

    // Delete subscription
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('admin.subscriptions')
            ->with('success', 'Subscription deleted!');
    }

    // ---------------------------
    // AJAX: Update subscription from modal
    // ---------------------------
    public function adminUpdate(Request $request, $id)
    {
        $sub = Subscription::findOrFail($id);

        $request->validate([
            'plan'        => 'required|string|in:monthly,lifetime',
            'status'      => 'required|string|in:active,expired',
            'description' => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        $sub->update([
            'plan'        => $request->plan,
            'status'      => $request->status,
            'description' => $request->description,
            'price'       => $request->price,
            'start_date'  => $request->start_date,
            'end_date'    => $request->plan === 'monthly' ? $request->end_date : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully!',
            'subscription' => [
                'id'          => $sub->id,
                'plan'        => $sub->plan,
                'status'      => $sub->status,
                'description' => $sub->description,
                'price'       => $sub->price,
                'start_date'  => $sub->start_date?->format('Y-m-d'),
                'end_date'    => $sub->plan === 'monthly' ? ($sub->end_date?->format('Y-m-d') ?? '-') : 'Lifetime',
            ]
        ]);
    }
}
