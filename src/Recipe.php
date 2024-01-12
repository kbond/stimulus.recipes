<?php

namespace App;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Recipe
{
    public readonly string $title;
    public readonly string $description;
    public readonly ?string $demo;

    /** @var string[] */
    public readonly array $credit;

    /**
     * @param array{
     *     title: string,
     *     description: string,
     *     demo?: string,
     *     credit?: string|string[],
     * } $manifest
     */
    public function __construct(public readonly string $name, array $manifest)
    {
        $this->title = $manifest['title'] ?? throw new \InvalidArgumentException('Missing title');
        $this->description = $manifest['description'] ?? throw new \InvalidArgumentException('Missing description');
        $this->demo = $manifest['demo'] ?? null;
        $this->credit = (array) ($manifest['credit'] ?? []);
    }
}