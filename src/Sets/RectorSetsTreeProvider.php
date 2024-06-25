<?php

declare(strict_types=1);

namespace App\Sets;

use App\PhpParser\SimplePhpParser;
use App\RuleFilter\ValueObject\RectorSet;
use PhpParser\Node\Name;
use PhpParser\NodeFinder;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;
use Rector\Contract\Rector\RectorInterface;
use Rector\Doctrine\Set\SetProvider\DoctrineSetProvider;
use Rector\PHPUnit\Set\SetProvider\PHPUnitSetProvider;
use Rector\Set\Contract\SetProviderInterface;
use Rector\Set\SetProvider\CoreSetProvider;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SetProvider\SymfonySetProvider;
use Webmozart\Assert\Assert;

final class RectorSetsTreeProvider
{
    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $rectorSets = [];

    /**
     * @return array<string, RectorSet[]>
     */
    public function provideGrouped(): array
    {
        $rectorSetsByGroup = [];
        foreach ($this->rectorSets as $rectorSet) {
            $rectorSetsByGroup[$rectorSet->getGroupName()][] = $rectorSet;
        }

        return $rectorSetsByGroup;
    }

    /**
     * @return RectorSet[]
     */
    public function provide(): array
    {
        if ($this->rectorSets !== []) {
            return $this->rectorSets;
        }

        /** @var array<SetProviderInterface> $setProviders */
        $setProviders = [
            new CoreSetProvider(),
            new SymfonySetProvider(),
            new PHPUnitSetProvider(),
            new DoctrineSetProvider(),
        ];

        $rectorSets = [];

        /** @var SetProviderInterface $setProvider */
        foreach ($setProviders as $setProvider) {
            foreach ($setProvider->provide() as $set) {
                $setFilePath = $set->getSetFilePath();

                if ($set->getSetFilePath() === SetList::CODE_QUALITY) {
                    $rectorClasses = CodeQualityLevel::RULES;
                } elseif ($set->getSetFilePath() === SetList::DEAD_CODE) {
                    $rectorClasses = DeadCodeLevel::RULES;
                } elseif ($set->getSetFilePath() === SetList::TYPE_DECLARATION) {
                    $rectorClasses = TypeDeclarationLevel::RULES;
                } else {
                    $rectorClasses = $this->resolveRectorClassesFromSetFilePath($setFilePath);
                }

                $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
            }
        }

        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }

    /**
     * @return array<class-string<RectorInterface>>
     */
    private function resolveRectorClassesFromSetFilePath(string $configFilePath): array
    {
        Assert::fileExists($configFilePath);

        $nodes = (new SimplePhpParser())->parseFile($configFilePath);
        $nodeFinder = new NodeFinder();

        $rectorClassesNames = $nodeFinder->find($nodes, function (\PhpParser\Node $node): bool {
            if (! $node instanceof Name) {
                return false;
            }

            return str_ends_with($node->toString(), 'Rector');
        });

        $rectorClasses = [];

        /** @var Name[] $rectorClassesNames */
        foreach ($rectorClassesNames as $rectorClassName) {
            $rectorClass = $rectorClassName->toString();
            /** @var class-string<RectorInterface> $rectorClass */
            $rectorClasses[] = $rectorClass;
        }

        return $rectorClasses;
    }
}
