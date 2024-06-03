<?php

declare(strict_types=1);

namespace Rector\Website\Controller;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rector\Website\FileSystem\RectorFinder;
use Rector\Website\Sets\RectorSetsTreeFactory;
use Rector\Website\Utils\ClassNameResolver;
use Rector\Website\ValueObject\RichRuleDefinition;
use Symfony\Component\Finder\Finder;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FilterRectorController extends Controller
{
    public function __construct(
        private readonly RectorFinder          $rectorFinder,
        private readonly RectorSetsTreeFactory $setsTreeFactory,
    ) {
    }

    public function __invoke(Request $request): View
    {
        $coreRectorRules = $this->rectorFinder->findCore();

        $isFilterEnabled = false;
        $query = $request->get('query');

        // at least 3 chars to invoke
        if (strlen($query) > 2) {
            $isFilterEnabled = true;
            $queryParts = explode(' ', $query);

            $ruleDefinitionsAndRanks = [];

            foreach ($coreRectorRules as $ruleDefinition) {
                $score = $this->computeSearchMatchingScore($queryParts, $ruleDefinition);
                if ($score === 0) {
                    continue;
                }

                // what set is this rules part of?
                $setsFileInfos = Finder::create()
                    ->files()
                    ->in(__DIR__ . '/../../../vendor/rector/rector/src/Config')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-doctrine/config/sets')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-symfony/config/sets')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-phpunit/config/sets')
                    // @todo add unofficial extensions as well
                    ->name('*.php')
                    ->files()
                    ->getIterator();

                $matchingSetFiles = [];
                foreach ($setsFileInfos as $setFileInfo) {
                    if (str_contains($setFileInfo->getContents(), $ruleDefinition->getRuleClass())) {
                        $matchingSetFiles[] = $setFileInfo->getRealPath();
                    }
                }

                // @todo find sets lsits
                // what set is this rules part of?
                $setsListFileInfos = Finder::create()
                    ->files()

                    ->in(__DIR__ . '/../../../vendor/rector/rector/src/Set')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-doctrine/src/Set')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-symfony/src/Set')
                    ->in(__DIR__ . '/../../../vendor/rector/rector/vendor/rector/rector-phpunit/src/Set')
                    // @todo add unofficial extensions as well
                    ->name('*SetList.php')
                    ->files()
                    ->getIterator();

                foreach ($setsListFileInfos as $setListFileInfo) {
                    $setListContents = $setListFileInfo->getContents();
                    $setListClassName = ClassNameResolver::resolveFromFileContents($setListContents);
                    \Webmozart\Assert\Assert::string($setListClassName);
                    \Webmozart\Assert\Assert::classExists($setListClassName);

                    // $setList = new $setListClassName();
                    $setListReflectionClass = new \ReflectionClass($setListClassName);

                    // @sort list names to constnats to file paths to included class names
                    // trree :)
                    dump($setListReflectionClass->getConstants());
                }

                die;

                // @todo reverse-engineer sets to get the either set constant or predefeind set name
                dump($matchingSetFiles);
                dump($ruleDefinition->getRuleClass());
                die;

                $ruleDefinitionsAndRanks[] = new RichRuleDefinition($ruleDefinition, $score);
            }

            usort(
                $ruleDefinitionsAndRanks,
                function (
                    RichRuleDefinition $firstRuleDefinitionAndRank,
                    RichRuleDefinition $secondRuleDefinitionAndRank
                ): int {
                    return $secondRuleDefinitionAndRank->getRank() <=> $firstRuleDefinitionAndRank->getRank();
                }
            );

            $coreRectorRules = [];
            foreach ($ruleDefinitionsAndRanks as $ruleDefinitionsAndRank) {
                $coreRectorRules[] = $ruleDefinitionsAndRank->getRuleDefinition();
            }

            // get max 10 results
            $coreRectorRules = array_slice($coreRectorRules, 0, 10);
        }

        return \view('homepage/filter-rector', [
            'page_title' => 'Filter Rector',
            'coreRectorRules' => $coreRectorRules,
            'isFilterEnabled' => $isFilterEnabled,
        ]);
    }

    /**
     * @param string[] $queryParts
     */
    private function computeSearchMatchingScore(array $queryParts, RuleDefinition $ruleDefinition): int
    {
        $score = 0;

        foreach ($queryParts as $queryPart) {
            if (str_contains(strtolower($ruleDefinition->getRuleClass()), strtolower($queryPart))) {
                ++$score;
            }
        }

        foreach ($queryParts as $queryPart) {
            if (str_contains($ruleDefinition->getDescription(), $queryPart)) {
                ++$score;
            }
        }

        return $score;
    }
}