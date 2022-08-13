<?php declare(strict_types=1);

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Sweikenb\Library\Markdown\Service\MarkdownInterceptorService;

class MarkdownInterceptorServiceTest extends TestCase
{
    public function getTitles(): iterable
    {
        yield [
            true,
            <<<MD
# Some headline

## Some other headline

### Even more headline
MD,
            'Some headline',
        ];

        yield [
            true,
            <<<MD
## Some other headline

# Some headline

### Even more headline
MD,
            'Some headline',
        ];

        yield [
            false,
            <<<MD
## Some other headline

# Some headline

### Even more headline
MD,
            'Some other headline',
        ];

        yield [
            true,
            <<<MD
**Not a headline**
MD,
            null,
        ];

        yield [
            false,
            <<<MD
**Not a headline**
MD,
            null,
        ];
    }

    /**
     * @covers       \Sweikenb\Library\Markdown\Service\MarkdownInterceptorService::getFirstTitle
     */
    public function testGetFirstTitle(): void
    {
        $service = new MarkdownInterceptorService();
        foreach ($this->getTitles() as $row) {
            [$respectHeadlineWeight, $markdown, $expected] = $row;
            $this->assertSame($expected, $service->getFirstTitle($markdown, $respectHeadlineWeight));
        }
    }
}
