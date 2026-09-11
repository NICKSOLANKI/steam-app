<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Library;

class LibraryController extends Controller
{
    // Show all users + total games purchased
    public function index()
    {
        $users = User::withCount('libraries')->get();
        return view('ADMIN.lb', compact('users'));
    }

    // Show specific user library
    public function showUserLibrary($userId)
    {
        $user = User::with('libraries')->findOrFail($userId);
        return view('ADMIN.user_library', compact('user'));
    }
}
