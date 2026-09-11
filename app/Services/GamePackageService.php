<?php

namespace App\Services;

use App\Models\Download;
use ZipArchive;

class GamePackageService
{
    public function ensurePackage(Download $download): string
    {
        $dir = storage_path('app/game_packages');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $slug = preg_replace('/[^A-Za-z0-9]+/', '_', $download->game_title);
        $path = $dir . DIRECTORY_SEPARATOR . $download->library_id . '_' . $slug . '.zip';

        if (is_file($path) && filesize($path) > 1024) {
            return $path;
        }

        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Unable to create game package.');
        }

        $title = $download->game_title;
        $readme = "STEAM GAME PACKAGE\r\n"
            . "==================\r\n\r\n"
            . "Title: {$title}\r\n"
            . "Purchased via STEAM Library\r\n"
            . "Installed from your Downloads page.\r\n\r\n"
            . "Run Play.bat to launch the game stub.\r\n";

        $launcher = "@echo off\r\n"
            . "title {$title}\r\n"
            . "echo.\r\n"
            . "echo  ========================================\r\n"
            . "echo    Launching {$title}\r\n"
            . "echo  ========================================\r\n"
            . "echo.\r\n"
            . "echo  Game package downloaded from STEAM.\r\n"
            . "pause\r\n";

        $zip->addFromString('README.txt', $readme);
        $zip->addFromString('Play.bat', $launcher);
        $zip->addFromString('data/manifest.json', json_encode([
            'title' => $title,
            'library_id' => $download->library_id,
            'generated_at' => now()->toIso8601String(),
        ], JSON_PRETTY_PRINT));

        $payload = str_repeat("STEAM-GAME-DATA-{$title}-", 180000);
        $zip->addFromString('data/game.bin', $payload);

        $zip->close();

        return $path;
    }

    public function downloadFilename(Download $download): string
    {
        $slug = preg_replace('/[^A-Za-z0-9]+/', '_', $download->game_title);
        return $slug . '.zip';
    }
}
