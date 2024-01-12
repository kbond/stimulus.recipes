<?php

namespace App;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireServiceClosure;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @implements \IteratorAggregate<string,Recipe>
 */
final class RecipeRegistry implements \Countable, \IteratorAggregate
{
    public function __construct(
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
        private CacheInterface $cache,

        /** @var \Closure():Environment */
        #[AutowireServiceClosure(Environment::class)]
        private \Closure $twig,
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
        return $this->cache->get('recipes', function () {
            $recipes = [];
            $files = glob($this->recipeDir().'/*.twig') ?: throw new \LogicException('No recipes found');

            foreach ($files as $file) {
                $name = basename($file, '.twig');
                $manifest = ($this->twig)()->load('recipes/'.basename($file))->renderBlock('manifest');
                $manifest = Yaml::parse($manifest);

                if (!is_array($manifest)) {
                    throw new \RuntimeException(sprintf('Invalid manifest for recipe "%s"', $name));
                }

                $recipes[$name] = new Recipe(
                    name: $name,
                    title: $manifest['title'] ?? throw new \LogicException(sprintf('Recipe "%s" is missing a title', $name)),
                    description: $manifest['description'] ?? throw new \LogicException(sprintf('Recipe "%s" is missing a description', $name)),
                    controller: $this->projectDir.'/'.($manifest['controller'] ?? throw new \LogicException(sprintf('Recipe "%s" is missing a controller', $name))),
                    credit: (array) ($manifest['credit'] ?? []),
                    jsDependencies: $manifest['dependencies']['js'] ?? [],
                );
            }

            return $recipes;
        });
    }

    private function recipeDir(): string
    {
        return $this->projectDir.'/templates/recipes';
    }
}
