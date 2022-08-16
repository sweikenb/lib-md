<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Api;

interface MarkdownParserInterface
{
    public function parseToAtomicHtml(string $markdown): string;
}
