<?php

declare(strict_types=1);

namespace Rector\Website\GitHubMagicLink\LinkFactory;

use Nette\Utils\Strings;
use Rector\Website\Demo\Entity\RectorRun;
use Rector\Website\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use Rector\Website\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;

/**
 * @see \Rector\Website\GitHubMagicLink\Twig\FixtureLinkTwigExtension
 */
final class FixtureLinkFactory
{
    /**
     * @var string
     */
    private const BASE_URL = 'https://github.com/rectorphp/rector-src/new/main';

    /**
     * @var string
     * @see https://regex101.com/r/ABk9gM/1
     */
    private const PACKAGE_NAME_REGEX = '#^rules-tests\/(?<Package>[^\/]+)\/Rector#';

    /**
     * @var string[]
     */
    private const RECTOR_PACKAGE_NAMES = ['Symfony', 'PHPUnit', 'Doctrine', 'CakePHP', 'PHPOffice', 'Laravel'];

    /**
     * @var string
     */
    private const DOWNGRADE_PACKAGE_PREFIX = 'DowngradePhp';

    public function __construct(
        private readonly FixtureBodyFactory $fixtureBodyFactory,
        private readonly PullRequestDescriptionFactory $pullRequestDescriptionFactory
    ) {
    }

    public function create(RectorRun $rectorRun): string
    {
        $content = $this->fixtureBodyFactory->create($rectorRun);

        $expectedRectorTestPath = $rectorRun->getExpectedRectorTestPath();

        $message = 'Add failing test fixture for ' . $rectorRun->getRectorShortClass();
        $description = $this->pullRequestDescriptionFactory->create($rectorRun);

        $match = Strings::match($expectedRectorTestPath, self::PACKAGE_NAME_REGEX);
        $link = $this->resolveLink($expectedRectorTestPath, $rectorRun, $content, $message, $description);

        if ($match === null) {
            return $link;
        }

        if (! in_array($match['Package'], self::RECTOR_PACKAGE_NAMES, true)) {
            if (str_starts_with((string) $match['Package'], self::DOWNGRADE_PACKAGE_PREFIX)) {
                return str_replace('rector-src', 'rector-downgrade-php', $link);
            }

            return $link;
        }

        $package = strtolower($match['Package']);
        $link = str_replace('rules-tests/' . $match['Package'] . '/Rector', 'tests/Rector', $link);

        return str_replace('rector-src', 'rector-' . $package, $link);
    }

    private function resolveLink(
        string $expectedRectorTestPath,
        RectorRun $rectorRun,
        string $content,
        string $message,
        string $description
    ): string {
        return self::BASE_URL . '/'
            . $expectedRectorTestPath
            . '?filename=Fixture/' . $rectorRun->getFixtureFileName()
            . '&value=' . urlencode($content)
            . '&message=' . urlencode($message)
            . '&description=' . urlencode($description);
    }
}
