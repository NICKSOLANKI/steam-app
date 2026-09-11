<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Library;

class LibraryController extends Controller
{
    public function index()
    {
        $libraryItems = Library::where('user_id', auth()->id())->get();
        return view('managegame.library', compact('libraryItems'));
    }

    // Optional: Return JSON for modal
    public function modal()
    {
        $libraryItems = Library::where('user_id', auth()->id())->get();
        return response()->json($libraryItems);
    }
}