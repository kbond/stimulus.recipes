<?php

namespace App;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireServiceClosure;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 *
 * @implements \IteratorAggregate<string,Recipe>
 */
final class RecipeRegistry implements \Countable, \IteratorAggregate, CacheWarmerInterface
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
                $template = ($this->twig)()->load('recipes/'.basename($file));
                $source = file_get_contents($file) ?: throw new \RuntimeException(sprintf('Unable to read file "%s"', $file));
                $manifest = Yaml::parse($template->renderBlock('manifest'));

                // manually parse the demo block as you can't render the source of a block with twig
                if (!\preg_match('#{%\s?block demo\s?%}(.+){%\s?endblock\s?%}#s', $source, $matches)) {
                    throw new \RuntimeException(sprintf('Missing demo block for recipe "%s"', $name));
                }

                if (!is_array($manifest)) {
                    throw new \RuntimeException(sprintf('Invalid manifest for recipe "%s"', $name));
                }

                $recipes[$name] = new Recipe($name, trim($matches[1]), $manifest, $this->projectDir); // @phpstan-ignore-line
            }

            return $recipes;
        });
    }

    public function warmUp(string $cacheDir, string $buildDir = null): array
    {
        // warm the cache
        $this->all();

        return [];
    }

    public function isOptional(): bool
    {
        return true;
    }

    private function recipeDir(): string
    {
        return $this->projectDir.'/templates/recipes';
    }
}
