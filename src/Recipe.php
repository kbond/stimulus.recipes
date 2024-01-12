<?php

namespace App;

use App\Recipe\File;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
final class Recipe
{
    public readonly File $controller;

    /**
     * @param string[] $credit
     * @param string[] $jsDependencies
     */
    public function __construct(
        public readonly string $name,
        public readonly string $title,
        public readonly string $description,
        string $controller,
        public readonly array $credit,

        #[SerializedName('js_dependencies')]
        public readonly array $jsDependencies,
    ) {
        $this->controller = new File($controller);
    }

    public function template(): string
    {
        return sprintf('recipes/%s.twig', $this->name);
    }
}
