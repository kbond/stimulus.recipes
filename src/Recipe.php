<?php

namespace App;

use App\Recipe\File;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @phpstan-type Manifest array{
 *     title: string,
 *     description: string,
 *     credit?: string|string[],
 *     dependencies?: array{
 *          js?: string[],
 *          node?: string[],
 *          asset_mapper?: string[],
 *          php?: string[],
 *     },
 *     files?: string|string[],
 * }
 */
final class Recipe
{
    public readonly string $title;
    public readonly string $description;

    /** @var string[] */
    public readonly array $references;

    /** @var array{npm: string[], asset_mapper: string[], php: string[]} */
    public readonly array $dependencies;

    /** @var File[] */
    public array $files;

    /**
     * @param Manifest $manifest
     */
    public function __construct(public readonly string $name, array $manifest, string $projectDir)
    {
        $this->title = $manifest['title'] ?? throw new \LogicException(sprintf('Missing title for recipe "%s"', $name));
        $this->description = $manifest['description'] ?? throw new \LogicException(sprintf('Missing description for recipe "%s"', $name));
        $this->references = (array) ($manifest['references'] ?? []);
        $this->dependencies = [
            'npm' => $manifest['dependencies']['npm'] ?? $manifest['dependencies']['js'] ?? [],
            'asset_mapper' => $manifest['dependencies']['asset_mapper'] ?? $manifest['dependencies']['js'] ?? [],
            'php' => $manifest['dependencies']['php'] ?? [],
        ];
        $this->files = array_map(
            static fn (string $path) => new File(
                $path,
                file_get_contents(sprintf('%s/%s', $projectDir, $path)) ?: throw new \RuntimeException(sprintf('Unable to read file "%s"', $path)),
            ),
            (array) ($manifest['files'] ?? [])
        );
    }

    public function hasJsDependencies(): bool
    {
        return $this->dependencies['npm'] || $this->dependencies['asset_mapper'];
    }

    public function template(): string
    {
        return sprintf('recipes/%s.twig', $this->name);
    }
}
