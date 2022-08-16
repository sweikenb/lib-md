<?php declare(strict_types=1);

namespace Tests\Model;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sweikenb\Library\Markdown\Model\AutoSelectMarkdownParser;
use Sweikenb\Library\Markdown\Model\PandocMarkdownParser;
use Sweikenb\Library\Markdown\Model\ParsedownMarkdownParser;

class AutoSelectMarkdownParserTest extends TestCase
{
    /**
     * @covers \Sweikenb\Library\Markdown\Model\ParsedownMarkdownParser::__construct
     */
    public function testAutoSelectParsedown(): void
    {
        $model = new AutoSelectMarkdownParser('');
        $ref = new ReflectionClass($model);
        $prop = $ref->getProperty('parser');

        $this->assertInstanceOf(ParsedownMarkdownParser::class, $prop->getValue($model));
    }

    /**
     * @covers \Sweikenb\Library\Markdown\Model\PandocMarkdownParser::__construct
     */
    public function testAutoSelectPandoc(): void
    {
        $model = new AutoSelectMarkdownParser();
        $ref = new ReflectionClass($model);
        $prop = $ref->getProperty('parser');

        $this->assertInstanceOf(PandocMarkdownParser::class, $prop->getValue($model));
    }
}
