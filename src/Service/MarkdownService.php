<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Service;

use Sweikenb\Library\Markdown\Api\MarkdownParserInterface;
use Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser;

class MarkdownService
{
    private MarkdownParserInterface $markdownParser;

    /**
     * @var array<int, string>
     */
    private array $mdFileExt;

    /**
     * @param array<int, string> $mdFileExt
     */
    public function __construct(?MarkdownParserInterface $markdownParser = null, array $mdFileExt = ['md', 'markdown'])
    {
        $this->markdownParser = $markdownParser ?? new AutoSelectMarkdownParser();
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
        return $this->markdownParser->parseToAtomicHtml($markdown);
    }

    public function isMarkdownFile(string $filename): bool
    {
        $pattern = sprintf('/\.(%s)$/i', implode('|', $this->mdFileExt));
        return preg_match($pattern, $filename) === 1;
    }
}
