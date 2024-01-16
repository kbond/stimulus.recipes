<?php

namespace App\Twig\Components;

use Spatie\ShikiPhp\Shiki;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class HighlightCode
{
    public string $code;
    public string $language;
    public ?string $theme = null;
    public ?array $highlightLines = null;
    public ?array $addLines = null;
    public ?array $deleteLines = null;
    public ?array $focusLines = null;

    public function __construct(private CacheInterface $cache)
    {
    }

    public function highlighted(): string
    {
        // we cache the highlighted code to avoid calling shiki for the same code block on every request
        return $this->cache->get(
            sprintf('highlight-code-%s_%s_%s', crc32($this->code), $this->language, $this->theme),
            fn () => Shiki::highlight(
                code: $this->code,
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
