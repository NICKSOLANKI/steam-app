<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Download;
use App\Models\Library;
use App\Services\GamePackageService;

class DownloadController extends Controller
{
    public function index()
    {
        $downloads = Download::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        $activeCount = Download::where('user_id', Auth::id())
            ->whereIn('status', ['downloading', 'queued'])
            ->count();

        return view('downloads.index', compact('downloads', 'activeCount'));
    }

    public function store(Request $request, GamePackageService $packages)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
        ]);

        $libraryItem = Library::where('id', $request->library_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $existingDownload = Download::where('user_id', Auth::id())
            ->where('library_id', $request->library_id)
            ->whereIn('status', ['downloading', 'queued', 'paused'])
            ->first();

        if ($existingDownload) {
            return response()->json([
                'success' => false,
                'message' => 'This game is already in your downloads.',
                'download_id' => $existingDownload->id,
                'redirect' => route('downloads.show', $existingDownload->id),
            ]);
        }

        $completed = Download::where('user_id', Auth::id())
            ->where('library_id', $request->library_id)
            ->where('status', 'completed')
            ->first();

        if ($completed) {
            return response()->json([
                'success' => true,
                'message' => 'Already installed.',
                'download' => $completed,
                'download_id' => $completed->id,
                'redirect' => route('downloads.show', $completed->id),
            ]);
        }

        $download = Download::create([
            'user_id' => Auth::id(),
            'library_id' => $request->library_id,
            'game_title' => $libraryItem->game_title,
            'game_image' => $libraryItem->game_image,
            'total_size' => 0,
            'downloaded_size' => 0,
            'current_speed' => 0,
            'peak_speed' => 0,
            'status' => 'queued',
            'progress' => 0,
            'install_path' => 'Downloads\\' . preg_replace('/[^A-Za-z0-9]+/', '_', $libraryItem->game_title) . '.zip',
        ]);

        $path = $packages->ensurePackage($download);
        $sizeMb = round(filesize($path) / 1048576, 3);

        $download->update(['total_size' => $sizeMb]);
        $download->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Download added to queue.',
            'download' => $download,
            'download_id' => $download->id,
            'redirect' => route('downloads.show', $download->id),
        ]);
    }

    public function show($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('downloads.show', compact('download'));
    }

    public function file($id, GamePackageService $packages)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $path = $packages->ensurePackage($download);
        $filename = $packages->downloadFilename($download);

        if ($download->status === 'queued') {
            $download->update([
                'status' => 'downloading',
                'started_at' => $download->started_at ?? now(),
                'total_size' => round(filesize($path) / 1048576, 3),
            ]);
        }

        return response()->download($path, $filename, [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'private, no-transform',
        ]);
    }

    public function updateProgress(Request $request, $id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($download->status === 'completed') {
            return response()->json(['download' => $download]);
        }

        if ($request->has('progress')) {
            $progress = max(0, min(100, (float) $request->input('progress')));
            $downloadedSize = max(0, (float) $request->input('downloaded_size', $download->downloaded_size));
            $currentSpeed = max(0, (float) $request->input('current_speed', 0));
            $status = $request->input('status', $download->status);
            $allowed = ['queued', 'downloading', 'paused', 'completed', 'cancelled'];
            if (!in_array($status, $allowed, true)) {
                $status = $download->status;
            }

            $peakSpeed = max((float) $download->peak_speed, $currentSpeed);
            $completedAt = $download->completed_at;

            if ($progress >= 100 || $status === 'completed') {
                $progress = 100;
                $status = 'completed';
                $downloadedSize = $download->total_size;
                $currentSpeed = 0;
                $completedAt = $completedAt ?? now();
            }

            $download->update([
                'downloaded_size' => round($downloadedSize, 6),
                'current_speed' => round($currentSpeed, 2),
                'peak_speed' => round($peakSpeed, 2),
                'progress' => (int) round($progress),
                'status' => $status,
                'started_at' => $download->started_at ?? now(),
                'completed_at' => $completedAt,
            ]);

            return response()->json(['download' => $download->fresh()]);
        }

        if ($download->status === 'queued') {
            $download->update([
                'status' => 'downloading',
                'started_at' => now(),
            ]);
        }

        if ($download->status === 'downloading') {
            $baseSpeed = rand(8, 45);
            $speedVariation = (rand(-15, 15) / 100) * $baseSpeed;
            $currentSpeed = max(1, $baseSpeed + $speedVariation);

            $chunkMb = $currentSpeed * 0.35;
            $downloadedSize = min($download->total_size, $download->downloaded_size + $chunkMb);
            $progress = $download->total_size > 0
                ? min(100, ($downloadedSize / $download->total_size) * 100)
                : 0;
            $peakSpeed = max($download->peak_speed, $currentSpeed);

            $status = 'downloading';
            $completedAt = null;

            if ($progress >= 100) {
                $downloadedSize = $download->total_size;
                $progress = 100;
                $status = 'completed';
                $completedAt = now();
                $currentSpeed = 0;
            }

            $download->update([
                'downloaded_size' => round($downloadedSize, 6),
                'current_speed' => round($currentSpeed, 2),
                'peak_speed' => round($peakSpeed, 2),
                'progress' => (int) round($progress),
                'status' => $status,
                'completed_at' => $completedAt,
            ]);
        }

        return response()->json(['download' => $download->fresh()]);
    }

    public function pause($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (in_array($download->status, ['downloading', 'queued'], true)) {
            $download->update([
                'status' => 'paused',
                'current_speed' => 0,
            ]);
        }

        return response()->json(['download' => $download->fresh()]);
    }

    public function resume($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($download->status === 'paused' || $download->status === 'queued') {
            $download->update(['status' => 'downloading']);
        }

        return response()->json(['download' => $download->fresh()]);
    }

    public function cancel($id)
    {
        $download = Download::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $download->update([
            'status' => 'cancelled',
            'current_speed' => 0,
        ]);

        return response()->json(['message' => 'Download cancelled', 'download' => $download->fresh()]);
    }

    public function activeCount()
    {
        $count = Download::where('user_id', Auth::id())
            ->whereIn('status', ['downloading', 'queued'])
            ->count();

        $progress = 0;
        $active = Download::where('user_id', Auth::id())
            ->where('status', 'downloading')
            ->first();

        if ($active) {
            $progress = $active->progress;
        }

        return response()->json([
            'count' => $count,
            'active_download' => $active,
            'active_progress' => $progress,
        ]);
    }

    public function recent()
    {
        $downloads = Download::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return response()->json(['downloads' => $downloads]);
    }
}