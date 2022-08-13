<?php declare(strict_types=1);

namespace Tests\Service;

use ParsedownExtra;
use PHPUnit\Framework\TestCase;
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
     */
    public function testToHtml(): void
    {
        $parsedown = new ParsedownExtra();
        $service = new MarkdownService();

        $this->assertSame(
            $parsedown->text(self::MARKDOWN),
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
     * @dataProvider filenamesProvider
     */
    public function testIsMarkdownFile(array $fileExt, string $filename, bool $expected): void
    {
        $service = new MarkdownService($fileExt);
        $this->assertSame($fileExt, $service->getFileExtensions());
        $this->assertSame($expected, $service->isMarkdownFile($filename));
    }
}
