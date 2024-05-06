<?php

namespace App\Services;

use App\Models\Panel;
use App\Models\PanelFile;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PanelService
{
    public function __construct(public Panel $panel)
    {
    }

    public function getStoragePath(): string
    {
        return storage_path(
            sprintf(
                'app/panels/%s',
                str($this->panel->id.' - '.$this->panel->name)->slug()
            )
        );
    }

    public function writeFile(string $path, string $contents): void
    {
        $path = $this->getStoragePath().DIRECTORY_SEPARATOR.$path;

        $filesystem = app(Filesystem::class);

        $filesystem->ensureDirectoryExists(
            pathinfo($path, PATHINFO_DIRNAME),
        );

        $filesystem->put($path, $contents);
    }

    public function deleteFile(PanelFile $file): void
    {
        $path = $this->getStoragePath().DIRECTORY_SEPARATOR.$file->path;

        $filesystem = app(Filesystem::class);

        // TODO: This should also delete empty directories

        $filesystem->delete($path);
    }

    public function zipFiles(): string
    {
        $zipPath = $this->getStoragePath().DIRECTORY_SEPARATOR.'panel.zip';

        $filesystem = app(Filesystem::class);

        $filesystem->ensureDirectoryExists(
            pathinfo($zipPath, PATHINFO_DIRNAME),
        );

        $zip = new ZipArchive();

        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = $this->panel->panelFiles;

        foreach ($files as $file) {
            if (! $filesystem->exists($this->getStoragePath().DIRECTORY_SEPARATOR.$file->path)) {
                continue;
            }

            $zip->addFile(
                $this->getStoragePath().DIRECTORY_SEPARATOR.$file->path,
                $file->path,
            );
        }

        $zip->close();

        $filesystem->move($zipPath, storage_path(sprintf('app/public/%s.zip',
            str($this->panel->id.' - '.$this->panel->name)->slug()
        )));

        return Storage::url(sprintf('%s.zip',
            str($this->panel->id.' - '.$this->panel->name)->slug()
        ));
    }
}
