<?php

namespace App;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @implements \IteratorAggregate<string,Recipe>
 *
 * @phpstan-import-type Manifest from Recipe
 */
final class RecipeRegistry implements \Countable, \IteratorAggregate
{
    /** @var array<string,Recipe> */
    private array $recipes;

    /**
     * @param array<string,Manifest> $config
     */
    public function __construct(
        #[Autowire('%recipes%')]
        array $config,
    ) {
        foreach ($config as $name => $manifest) {
            $this->recipes[$name] = new Recipe($name, $manifest);
        }
    }

    public function get(string $name): ?Recipe
    {
        return $this->recipes[$name] ?? null;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->recipes;
    }

    public function count(): int
    {
        return \count($this->recipes);
    }
}
