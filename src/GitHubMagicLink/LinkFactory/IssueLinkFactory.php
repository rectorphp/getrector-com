<?php

declare(strict_types=1);

namespace App\GitHubMagicLink\LinkFactory;

use App\Entity\RectorRun;
use App\GitHubMagicLink\BodyFactory\IssueBodyFactory;
use App\ValueObject\AppliedRule;

final class IssueLinkFactory
{
    /**
     * @var string
     */
    private const BASE_URL = 'https://github.com/rectorphp/rector/issues/new?labels=bug&template=1_Bug_report.md';

    public function __construct(
        private readonly IssueBodyFactory $issueBodyFactory
    ) {
    }

    /**
     * @api used in functions for blade
     */
    public function create(RectorRun $rectorRun): string
    {
        $appliedRules = $rectorRun->getAppliedRules();

        $title = $this->createTitle($appliedRules);
        $body = $this->issueBodyFactory->create($rectorRun);

        $encodedTitle = urlencode($title);
        $encodedBody = urlencode($body);

        return self::BASE_URL . '&title=' . $encodedTitle . '&body=' . $encodedBody;
    }

    /**
     * @param AppliedRule[] $appliedRules
     */
    private function createTitle(array $appliedRules): string
    {
        $shortClasses = [];
        foreach ($appliedRules as $appliedRule) {
            $shortClasses[] = $appliedRule->getShortClass();
        }

        $title = implode(', ', $shortClasses);
        return 'Incorrect behavior of ' . $title;
    }
}
