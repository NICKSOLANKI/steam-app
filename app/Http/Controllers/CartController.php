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
        $cartItems = Cart::where('user_id', auth()->id())->get();
        return view('managegame.cart', compact('cartItems'));
    }

    // Add game to cart
    public function add(Request $request)
    {
        $cart = new Cart();
        $cart->user_id = auth()->id();
        $cart->game_title = $request->game_title;
        $cart->game_image = $request->game_image;
        $cart->price = $request->price;
        $cart->quantity = $request->quantity ?? 1;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Game added to cart!');
    }

    // Remove item from cart
    public function remove($id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
        }
        return redirect()->route('cart.index')->with('success', 'Item removed!');
    }

    // Buy item and move to library
    public function buy(Request $request, $id)
    {
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
    }
}


