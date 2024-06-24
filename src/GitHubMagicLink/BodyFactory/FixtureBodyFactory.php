<?php

declare(strict_types=1);

namespace App\GitHubMagicLink\BodyFactory;

use App\Entity\RectorRun;
use Nette\Utils\Strings;

/**
 * @see \App\Tests\GitHubMagicLink\BodyFactory\FixtureBodyFactory\FixtureBodyFactoryTest
 */
final class FixtureBodyFactory
{
    /**
     * @var string
     */
    private const CHANGE_HALF_FIXTURE = <<<'CODE_SAMPLE'
<?php

__FIXTURE_NAMESPACE__

// what is expected code?
// should remain the same? delete part below ----- (included)

?>
CODE_SAMPLE;

    /**
     * @var string
     * @see https://regex101.com/r/wrs9yD/3
     */
    private const CLASS_START_REGEX = '#\n(final |abstract )?class#s';

    public function create(RectorRun $rectorRun): string
    {
        $changedHalf = $this->createChangedHaflWithNamespace($rectorRun);

        $content = $this->decorateContentWithNamespace($rectorRun);

        $contentLines = [];
        $contentLines[] = $content;
        $contentLines[] = '?>';
        $contentLines[] = '-----';
        $contentLines[] = $changedHalf;

        $content = implode(PHP_EOL, $contentLines);

        return $content . PHP_EOL;
    }

    private function createChangedHaflWithNamespace(RectorRun $rectorRun): string
    {
        $quotedPlaceholder = '#' . preg_quote('__FIXTURE_NAMESPACE__', '#') . '#';
        $namespaceLine = $this->createNamespaceLine($rectorRun);

        return Strings::replace(self::CHANGE_HALF_FIXTURE, $quotedPlaceholder, $namespaceLine);
    }

    private function decorateContentWithNamespace(RectorRun $rectorRun): string
    {
        $namespaceLine = $this->createNamespaceLine($rectorRun);

        return Strings::replace(
            $rectorRun->getContent(),
            self::CLASS_START_REGEX,
            PHP_EOL . $namespaceLine . PHP_EOL . PHP_EOL . '$1class'
        );
    }

    private function createNamespaceLine(RectorRun $rectorRun): string
    {
        return 'namespace ' . $rectorRun->getExpectedRectorTestNamespace() . ';';
    }
}
