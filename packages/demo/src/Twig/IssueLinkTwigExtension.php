<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Twig;

use Nette\Utils\Strings;
use Rector\Website\Demo\Entity\RectorRun;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IssueLinkTwigExtension extends AbstractExtension
{
    /**
     * @var string
     */
    private const BASE_URL = 'https://github.com/rectorphp/rector/issues/new?labels=bug&template=1_Bug_report.md';

    /**
     * @see https://stackoverflow.com/a/10601740/1348344
     * @var string
     */
    private const NEWLINE = '%0D%0A';

    /**
     * @see https://stackoverflow.com/a/5007362/1348344
     * @var string
     */
    private const HASHTAG = '%23';

    /**
     * @see https://stackoverflow.com/a/10896512/1348344
     * @var string
     */
    private const SEMICOLON = '%3B';

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        $twigFilter = new TwigFilter('issue_link', function (RectorRun $rectorRun): string {
            $appliedRules = $rectorRun->getAppliedShortRules();

            $title = implode(', ', $appliedRules);
            $title = 'Incorrect behavior of ' . $title;

            $body = '<!-- complete intro text -->' . self::NEWLINE . self::NEWLINE . 'See https://getrector.org/demo/' . $rectorRun->getId();

            $body .= self::NEWLINE . self::NEWLINE;

            // @todo config add too

            $body .= '## PHP File Content';
            $body .= self::NEWLINE . self::NEWLINE;

            $body .= '```php' . self::NEWLINE . $rectorRun->getContent() . self::NEWLINE . '```' . self::NEWLINE;
            $body .= self::NEWLINE . self::NEWLINE;

            $body .= '## Expected Behavior';
            $body .= '<!-- complete if change and what change is required -->' . self::NEWLINE . self::NEWLINE;

            $body = Strings::replace($body, "#\r\n#", self::NEWLINE);
            $body = Strings::replace($body, "#\##", self::HASHTAG);
            $body = Strings::replace($body, '#;#', self::SEMICOLON);

            return self::BASE_URL . '&title=' . $title . '&body=' . $body;
        });

        return [$twigFilter];
    }
}
