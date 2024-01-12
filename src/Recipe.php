<?php

namespace App;

use App\Recipe\File;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Recipe
{
    public readonly File $controller;

    public function __construct(
        public readonly string $name,
        public readonly string $title,
        public readonly string $description,
        string $controller,

        /** @var string[] */
        public readonly array $credit,

        /** @var array{js?: string[]} */
        private readonly array $dependencies,
    ) {
        $this->controller = new File($controller);
    }

    /**
     * @return string[]
     */
    public function jsDependencies(): array
    {
        return $this->dependencies['js'] ?? [];
    }

    public function template(): string
    {
        return sprintf('recipes/%s.twig', $this->name);
    }
}
