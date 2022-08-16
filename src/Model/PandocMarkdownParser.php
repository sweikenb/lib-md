<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Model;

use Sweikenb\Library\Markdown\Api\MarkdownParserInterface;
use Sweikenb\Library\Markdown\Exceptions\MarkdownParserException;

class PandocMarkdownParser implements MarkdownParserInterface
{
    private string $pandocBin;

    /**
     * @throws MarkdownParserException
     */
    public function __construct(string $pandocBin)
    {
        $this->pandocBin = $pandocBin;
        if (!is_executable($this->pandocBin)) {
            throw new MarkdownParserException(
                sprintf(
                    'The provided pandoc binary is not executable for the current user: %s',
                    $this->pandocBin
                )
            );
        }
    }

    public function parseToAtomicHtml(string $markdown): string
    {
        $cmd = sprintf(
            '%s -f markdown -t html --strip-empty-paragraphs --strip-comments --preserve-tabs --no-highlight',
            $this->pandocBin
        );

        $descriptors = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"], // stderr
        ];

        $html = '';
        $process = proc_open($cmd, $descriptors, $pipes);
        if (is_resource($process)) {
            // pipe the markdown to pandoc
            fwrite($pipes[0], $markdown);
            fclose($pipes[0]);

            // get the response html
            $html = (string)stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // close process
            proc_close($process);
        }

        return trim($html);
    }
}
