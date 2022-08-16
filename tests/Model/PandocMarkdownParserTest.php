<?php declare(strict_types=1);

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use Sweikenb\Library\Markdown\Exceptions\MarkdownParserException;
use Sweikenb\Library\Markdown\Model\PandocMarkdownParser;

class PandocMarkdownParserTest extends TestCase
{
    /**
     * @covers \Sweikenb\Library\Markdown\Model\PandocMarkdownParser::__construct
     */
    public function testPandocMissingBinary(): void
    {
        $this->expectException(MarkdownParserException::class);
        new PandocMarkdownParser('/invalid/binary');
    }
}
