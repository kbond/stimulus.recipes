<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Tempest\Highlight\Highlighter;

#[AsTwigComponent]
final class HighlightCode
{
    public ?string $code = null;
    public string $language;

    public function __construct(private Highlighter $highlighter = new Highlighter())
    {
    }

    public function highlight(?string $code = null): string
    {
        return $this->highlighter->parse(
            $code ?? $this->code ?? throw new \RuntimeException('Code must be passed as the content block or the code attribute.'),
            $this->language ?? throw new \RuntimeException('Language must be passed as the language attribute.'),
        );
    }
}
