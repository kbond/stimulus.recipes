<?php

namespace App;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @phpstan-type Manifest array{
 *     title: string,
 *     description: string,
 *     controller?: string,
 *     dependencies?: array{
 *         js?: string[],
 *     },
 *     demo?: string,
 *     credit?: string|string[],
 *  }
 */
final class Recipe
{
    public readonly string $title;
    public readonly string $description;
    public readonly ?string $demo;

    /** @var string[] */
    public readonly array $credit;

    /** @var array{js?: string[]} */
    private readonly array $dependencies;
    private readonly ?string $controller;

    /**
     * @param Manifest $manifest
     */
    public function __construct(public readonly string $name, array $manifest)
    {
        $this->title = $manifest['title'] ?? throw new \InvalidArgumentException('Missing title');
        $this->description = $manifest['description'] ?? throw new \InvalidArgumentException('Missing description');
        $this->controller = $manifest['controller'] ?? null;
        $this->demo = $manifest['demo'] ?? null;
        $this->credit = (array) ($manifest['credit'] ?? []);
        $this->dependencies = $manifest['dependencies'] ?? [];
    }

    public function controllerName(): ?string
    {
        return $this->controller ? basename($this->controller) : null;
    }

    public function controllerSource(): ?string
    {
        return $this->controller ? file_get_contents($this->controller) : null;
    }

    /**
     * @return string[]
     */
    public function jsDependencies(): array
    {
        return $this->dependencies['js'] ?? [];
    }
}
