<?php

namespace App\Recipe;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class File implements \JsonSerializable
{
    public function __construct(private readonly string $filename)
    {
    }

    public function name(): string
    {
        return basename($this->filename);
    }

    public function source(): string
    {
        return file_get_contents($this->filename) ?: throw new \RuntimeException(sprintf('Unable to read file "%s"', $this->filename));
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name(),
            'source' => $this->source(),
        ];
    }
}