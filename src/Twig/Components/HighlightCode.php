<?php

namespace App\Twig\Components;

use Spatie\ShikiPhp\Shiki;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class HighlightCode
{
    public ?string $code = null;
    public string $language;
    public ?string $theme = null;

    /** @var list<int>|null */
    public ?array $highlightLines = null;

    /** @var list<int>|null */
    public ?array $addLines = null;

    /** @var list<int>|null */
    public ?array $deleteLines = null;

    /** @var list<int>|null */
    public ?array $focusLines = null;

    public function __construct(private CacheInterface $cache)
    {
    }

    public function highlight(?string $code = null): string
    {
        $code ??= $this->code;

        if (null === $code) {
            throw new \RuntimeException('Code must be passed as the content block or the code attribute.');
        }

        // we cache the highlighted code to avoid calling shiki for the same code block on every request
        return $this->cache->get(
            sprintf('highlight-code-%s_%s_%s', crc32($code), $this->language, $this->theme),
            fn () => Shiki::highlight(
                code: $code,
                language: $this->language,
                theme: $this->theme,
                highlightLines: $this->highlightLines,
                addLines: $this->addLines,
                deleteLines: $this->deleteLines,
                focusLines: $this->focusLines,
            ),
        );
    }
}
