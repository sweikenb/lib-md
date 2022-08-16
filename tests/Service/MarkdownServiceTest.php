<?php declare(strict_types=1);

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Sweikenb\Library\Markdown\Exceptions\MarkdownParserException;
use Sweikenb\Library\Markdown\Model\PandocMarkdownParser;
use Sweikenb\Library\Markdown\Model\ParsedownMarkdownParser;
use Sweikenb\Library\Markdown\Service\MarkdownService;

class MarkdownServiceTest extends TestCase
{
    const MARKDOWN = <<<MD
# Hello World

Lorem **Ipsum**.
MD;

    /**
     * @covers \Sweikenb\Library\Markdown\Service\MarkdownService::__construct
     * @covers \Sweikenb\Library\Markdown\Service\MarkdownService::toHtml
     * @covers \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::__construct
     * @covers \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::parseToAtomicHtml
     * @covers \Sweikenb\Library\Markdown\Model\ParsedownMarkdownParser::__construct
     * @covers \Sweikenb\Library\Markdown\Model\ParsedownMarkdownParser::parseToAtomicHtml
     */
    public function testParsedownToHtml(): void
    {
        $service = new MarkdownService(new ParsedownMarkdownParser());
        $this->assertSame(
            <<<PANDOC_HTML
<h1>Hello World</h1>
<p>Lorem <strong>Ipsum</strong>.</p>
PANDOC_HTML,
            $service->toHtml(self::MARKDOWN)
        );
    }

    /**
     * @covers \Sweikenb\Library\Markdown\Service\MarkdownService::__construct
     * @covers \Sweikenb\Library\Markdown\Service\MarkdownService::toHtml
     * @covers \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::__construct
     * @covers \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::parseToAtomicHtml
     * @covers \Sweikenb\Library\Markdown\Model\PandocMarkdownParser::__construct
     * @covers \Sweikenb\Library\Markdown\Model\PandocMarkdownParser::parseToAtomicHtml
     * @throws MarkdownParserException
     */
    public function testPandocToHtml(): void
    {
        $pandocBin = trim(shell_exec('which pandoc'));
        $service = new MarkdownService(new PandocMarkdownParser($pandocBin));

        $this->assertSame(
            <<<PANDOC_HTML
<h1 id="hello-world">Hello World</h1>
<p>Lorem <strong>Ipsum</strong>.</p>
PANDOC_HTML,
            $service->toHtml(self::MARKDOWN)
        );
    }

    public function filenamesProvider(): array
    {
        $fileExt = ['md', 'markdown'];
        return [
            [$fileExt, '/abs/file.md', true],
            [$fileExt, '/abs/file.markdown', true],
            [$fileExt, '/abs/file.MARKDOWN', true],
            [$fileExt, 'rel_path.md', true],
            [$fileExt, 'rel_path.MD', true],
            [$fileExt, 'rel_path.markdown', true],

            [$fileExt, '/abs/no_ext', false],
            [$fileExt, 'rel_no_ext', false],
            [$fileExt, '/abs/foo.bar', false],
            [$fileExt, '/abs/foo.BAR', false],
            [$fileExt, 'foo.bar', false],
            [$fileExt, 'foo.BAR', false],

            [['bar'], 'foo.bar', true],
            [['bar'], '/abs/foo.BAR', true],
            [['bar'], 'foo.md', false],
            [['bar'], '/abs/foo.MD', false],
        ];
    }

    /**
     * @covers       \Sweikenb\Library\Markdown\Service\MarkdownService::__construct
     * @covers       \Sweikenb\Library\Markdown\Service\MarkdownService::isMarkdownFile
     * @covers       \Sweikenb\Library\Markdown\Service\MarkdownService::getFileExtensions
     * @covers       \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::__construct
     * @covers       \Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser::parseToAtomicHtml
     * @covers       \Sweikenb\Library\Markdown\Model\PandocMarkdownParser::__construct
     * @dataProvider filenamesProvider
     */
    public function testIsMarkdownFile(array $fileExt, string $filename, bool $expected): void
    {
        $service = new MarkdownService(null, $fileExt);
        $this->assertSame($fileExt, $service->getFileExtensions());
        $this->assertSame($expected, $service->isMarkdownFile($filename));
    }
}
