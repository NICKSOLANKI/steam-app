<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Library; // Make sure you have a Library model

class CartController extends Controller
{
    // Show all cart items for logged-in user
    public function index()
    {
        try {
            $cartItems = Cart::where('user_id', auth()->id())->get();
            return view('managegame.cart', compact('cartItems'));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Cart index error: ' . $e->getMessage());
            return view('managegame.cart', ['cartItems' => collect()]);
        }
    }

    // Add game to cart
    public function add(Request $request)
    {
        try {
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->game_title = $request->game_title;
            $cart->game_image = $request->game_image;
            $cart->price = $request->price;
            $cart->quantity = $request->quantity ?? 1;
            $cart->save();

            return redirect()->route('cart.index')->with('success', 'Game added to cart!');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Cart add error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Failed to add game to cart.');
        }
    }

    // Remove item from cart
    public function remove($id)
    {
        try {
            $cart = Cart::find($id);
            if ($cart) {
                $cart->delete();
            }
            return redirect()->route('cart.index')->with('success', 'Item removed!');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Cart remove error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Failed to remove item.');
        }
    }

    // Buy item and move to library
    public function buy(Request $request, $id)
    {
        try {
            $cartItem = Cart::where('id', $id)->where('user_id', auth()->id())->first();
            if (!$cartItem) {
                return response()->json(['status' => 'error', 'message' => 'Item not found!'], 404);
            }

            // Add to Library table
            $library = new Library();
            $library->user_id = auth()->id();
            $library->game_title = $cartItem->game_title;
            $library->game_image = $cartItem->game_image;
            $library->price = $cartItem->price;
            $library->save();

            // Remove from cart
            $cartItem->delete();

            // Return JSON for Razorpay
            return response()->json([
                'status' => 'success',
                'message' => $library->game_title.' purchased successfully!',
                'order_id' => $request->payment_id ?? 'N/A',
                'game_title' => $library->game_title,
                'game_image' => $library->game_image,
                'price' => $library->price,
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Cart buy error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Purchase failed. Please try again.'], 500);
        }
    }

    // Checkout all items in cart
    public function checkout(Request $request)
    {
        try {
            $cartItems = Cart::where('user_id', auth()->id())->get();
            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }

            // Add all items to library
            foreach ($cartItems as $cartItem) {
                $library = new Library();
                $library->user_id = auth()->id();
                $library->game_title = $cartItem->game_title;
                $library->game_image = $cartItem->game_image;
                $library->price = $cartItem->price;
                $library->save();
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            return redirect()->route('library.index')->with('success', 'Purchase completed successfully!');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'An error occurred during checkout. Please try again.');
        }
    }
}