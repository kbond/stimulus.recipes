<?php

namespace App\Recipe;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class File implements \JsonSerializable
{
    public function __construct(public readonly string $path, public readonly string $source)
    {
    }

    public function name(): string
    {
        return basename($this->path);
    }

    public function extension(): string
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'path' => $this->path,
            'source' => $this->source,
        ];
    }
}
