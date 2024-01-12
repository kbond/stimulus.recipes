<?php

namespace App;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @implements \IteratorAggregate<string,Recipe>
 */
final class RecipeRegistry implements \Countable, \IteratorAggregate
{
    /** @var Recipe[] */
    private array $recipes;

    public function __construct(
        #[Autowire('%kernel.project_dir%/recipes')]
        private string $path,
        private CacheInterface $cache,
    ) {
    }

    public function get(string $name): ?Recipe
    {
        return $this->all()[$name] ?? null;
    }

    public function getIterator(): \Traversable
    {
        yield from $this->all();
    }

    public function count(): int
    {
        return \count($this->all());
    }

    /**
     * @return array<string,Recipe>
     */
    public function all(): array
    {
        return $this->recipes ??= $this->cache->get('recipes', function () {
            $recipes = [];
            $files = glob($this->path.'/*.yaml') ?: throw new \RuntimeException('Unable to read recipe files.');

            foreach ($files as $file) {
                $name = basename($file, '.yaml');
                $manifest = Yaml::parse(\file_get_contents($file) ?: throw new \RuntimeException('Unable to read recipe file.'));

                if (!\is_array($manifest)) {
                    throw new \InvalidArgumentException('Recipe must be an array');
                }

                $recipes[$name] = new Recipe($name, $manifest); // @phpstan-ignore-line
            }

            return $recipes;
        });
    }
}
