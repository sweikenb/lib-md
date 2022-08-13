<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Service;

use ParsedownExtra;

class MarkdownService
{
    private ParsedownExtra $parsedown;

    /**
     * @var array<int, string>
     */
    private array $mdFileExt;

    /**
     * @param array<int, string> $mdFileExt
     */
    public function __construct(array $mdFileExt = ['md', 'markdown'])
    {
        $this->parsedown = new ParsedownExtra();
        $this->mdFileExt = $mdFileExt;
    }

    /**
     * @return array<int, string>
     */
    public function getFileExtensions(): array
    {
        return $this->mdFileExt;
    }

    public function toHtml(string $markdown): string
    {
        return $this->parsedown->text($markdown);
    }

    public function isMarkdownFile(string $filename): bool
    {
        $pattern = sprintf('/\.(%s)$/i', implode('|', $this->mdFileExt));
        return preg_match($pattern, $filename) === 1;
    }
}
