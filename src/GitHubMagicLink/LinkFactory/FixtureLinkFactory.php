<?php

declare(strict_types=1);

namespace App\GitHubMagicLink\LinkFactory;

use App\Entity\RectorRun;
use App\GitHubMagicLink\BodyFactory\FixtureBodyFactory;
use App\GitHubMagicLink\BodyFactory\PullRequestDescriptionFactory;
use Nette\Utils\Strings;

/**
 * @see \App\GitHubMagicLink\Twig\FixtureLinkTwigExtension
 */
final readonly class FixtureLinkFactory
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
    private const RECTOR_PACKAGE_NAMES = ['Symfony', 'PHPUnit', 'Doctrine'];

    /**
     * @var string
     */
    private const DOWNGRADE_PACKAGE_PREFIX = 'DowngradePhp';

    public function __construct(
        private FixtureBodyFactory $fixtureBodyFactory,
        private PullRequestDescriptionFactory $pullRequestDescriptionFactory
    ) {
    }

    /**
     * @api used in functions for blade
     */
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

        $expectedNamespace = $rectorRun->getExpectedRectorTestNamespace();
        $slashedexpectedNamespace = str_replace('\\', '/', $expectedNamespace);
        $slashedexpectedNamespace = str_replace(
            'Rector/Tests/' . $match['Package'] . '/',
            '',
            $slashedexpectedNamespace
        );
        $slashedexpectedNamespace = Strings::before($slashedexpectedNamespace, '/', 1);

        $package = strtolower($match['Package']);
        if (is_dir(
            __DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-' . $package . '/rules/' . $slashedexpectedNamespace
        )) {
            $link = str_replace(
                'rules-tests/' . $match['Package'] . '/Rector',
                'rules-tests/' . $slashedexpectedNamespace,
                $link
            );
            $link = str_replace(
                '/Fixture?filename',
                '/' . $rectorRun->getRectorShortClass() . '/Fixture?filename',
                $link
            );

            return str_replace('rector-src', 'rector-' . $package, $link);
        }

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
            . '?filename=' . $rectorRun->getFixtureFileName()
            . '&value=' . urlencode($content)
            . '&message=' . urlencode($message)
            . '&description=' . urlencode($description);
    }
}
