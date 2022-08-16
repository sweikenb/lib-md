<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Model;

use Sweikenb\Library\Markdown\Api\MarkdownParserInterface;
use Sweikenb\Library\Markdown\Exceptions\MarkdownParserException;

class AutoSelectMarkdownParser implements MarkdownParserInterface
{
    private MarkdownParserInterface $parser;

    /**
     * @throws MarkdownParserException
     */
    public function __construct(?string $pandocBin = null)
    {
        $pandocBin = $pandocBin ?? trim(shell_exec('which pandoc'));
        if (!empty($pandocBin)) {
            $this->parser = new PandocMarkdownParser($pandocBin);
        } else {
            $this->parser = new ParsedownMarkdownParser();
        }
    }

    public function parseToAtomicHtml(string $markdown): string
    {
        return $this->parser->parseToAtomicHtml($markdown);
    }
}
