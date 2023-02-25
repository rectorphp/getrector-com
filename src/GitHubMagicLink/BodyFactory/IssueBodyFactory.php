<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\BodyFactory;

use Rector\Website\Entity\RectorRun;

final class IssueBodyFactory
{
    /**
     * Copied from original issue template https://github.com/rectorphp/rector/.github/ISSUE_TEMPLATE/1_Bug_report.md
     *
     * @var string
     */
    private const ISSUE_BASIC_TABLE = '| Subject        | Details              |
| :------------- | :--------------------|
| Rector version | last dev-main      |
| Installed as   | composer dependency  |';

    public function create(RectorRun $rectorRun): string
    {
        $bodyLines = [];

        $bodyLines[] = '# Bug Report';
        $bodyLines[] = '<!-- First, thank you for reporting a bug. That takes time and we appreciate that! -->';
        $bodyLines[] = self::ISSUE_BASIC_TABLE;

        $bodyLines[] = '## Minimal PHP Code Causing Issue';
        $bodyLines[] = 'See https://getrector.com/demo/' . $rectorRun->getUuid();
        $bodyLines[] = '```php' . PHP_EOL . rtrim($rectorRun->getContent()) . PHP_EOL . '```';

        $appliedRules = $rectorRun->getAppliedRules();
        if ($appliedRules !== []) {
            $bodyLines[] = '### Responsible rules';
            foreach ($appliedRules as $appliedRule) {
                $bodyLines[] = '* `' . $appliedRule->getShortClass() . '`';
            }
        }

        $bodyLines[] = '## Expected Behavior';
        $bodyLines[] = '<!-- How should Rector change the code? Or should Rector skip it? -->';

        $body = implode(PHP_EOL . PHP_EOL, $bodyLines);
        return $body . PHP_EOL;
    }
}
