<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Model;

use ParsedownExtra;
use Sweikenb\Library\Markdown\Api\MarkdownParserInterface;

class ParsedownMarkdownParser implements MarkdownParserInterface
{
    private ParsedownExtra $parsedown;

    public function __construct()
    {
        $this->parsedown = new ParsedownExtra();
    }

    public function parseToAtomicHtml(string $markdown): string
    {
        return (string)$this->parsedown->text($markdown);
    }
}
