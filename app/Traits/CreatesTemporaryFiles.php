<?php

namespace App\Traits;

use Illuminate\Filesystem\FilesystemAdapter;

trait CreatesTemporaryFiles
{
    protected FilesystemAdapter $storage;

    protected string $path;

    public function createFile(string $extension): string
    {
        $this->path = 'tmp/' . uniqid() . ".$extension";
        $this->storage = \Storage::disk('local');

        dispatch(function () {
            $this->storage->delete($this->path);
        })->afterResponse();

        return $this->path;
    }

    public function put(mixed $content): bool|string
    {
        return $this->storage->put($this->path, $content);
    }
}
