<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ContentPreviewExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('content_preview', $this->contentPreview(...)),
        ];
    }

    public function contentPreview(?string $html): string
    {
        return html_entity_decode(
            strip_tags($html ?? ''),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
    }
}
