<?php declare(strict_types=1);

namespace Sweikenb\Library\Markdown\Service;

class MarkdownInterceptorService
{
    public function getFirstTitle(string $markdown, bool $respectHeadlineWeight = false): ?string
    {
        if ($respectHeadlineWeight === false) {
            // return the first headline match found
            if (preg_match_all('/\r?\n(#+(.*))/', "\n$markdown\n", $matches)) {
                return trim($matches[2][0]);
            }
        } else {
            // try to find headlines by importance h1 > h6
            for ($i = 1; $i <= 6; $i++) {
                $pattern = sprintf('/\r?\n(%s([^#]+))/', str_repeat('#', $i));
                if (preg_match_all($pattern, "\n$markdown\n", $matches)) {
                    return trim($matches[2][0]);
                }
            }
        }
        return null;
    }
}
