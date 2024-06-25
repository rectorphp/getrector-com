<?php

declare(strict_types=1);

namespace App\Sets;

use App\RuleFilter\ValueObject\RectorSet;
<<<<<<< HEAD
use Rector\Bridge\SetProviderCollector;
use Rector\Bridge\SetRectorsResolver;
use Rector\Set\Contract\SetInterface;
=======
use Rector\Doctrine\Set\SetProvider\DoctrineSetProvider;
use Rector\PHPUnit\Set\SetProvider\PHPUnitSetProvider;
use Rector\Set\Contract\SetInterface;
use Rector\Set\SetProvider\CoreSetProvider;
use Rector\Symfony\Set\SetProvider\SymfonySetProvider;
>>>>>>> 44a20f0 (make use of set providers)

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
            // skip empty sets, usually for deprecated/future compatibility reasons
            if ($rectorSet->getRuleCount() === 0) {
                continue;
            }

            $rectorSetsByGroup[$rectorSet->getGroupName()][$rectorSet->getSlug()] = $rectorSet;
        }

        return $rectorSetsByGroup;
    }

    /**
     * @todo cache this on build to json somehow to avoid unnecessary calls
     * @return RectorSet[]
     */
    public function provide(): array
    {
        if ($this->rectorSets !== []) {
            return $this->rectorSets;
        }

        $simplePhpParser = new SimplePhpParser();

        $setProviders = [
            new CoreSetProvider(),
            new SymfonySetProvider(),
            new PHPUnitSetProvider(),
            new DoctrineSetProvider(),
        ];

        $rectorSets = [];
        foreach ($setProviders as $setProvider) {
            foreach ($setProvider->provide() as $set) {

<<<<<<< HEAD
        $setProviderCollector = new SetProviderCollector();
        $setRectorsResolver = new SetRectorsResolver();

        foreach ($setProviderCollector->provideSets() as $set) {
            /** @var SetInterface $set */
            $rectorClasses = $setRectorsResolver->resolveFromFilePath($set->getSetFilePath());
            $rectorSets[] = new RectorSet($set->getGroupName(), $set->getName(), $rectorClasses);
=======
                new RectorSet();
                // SimplePhpParser $simplePhpParser
            }
>>>>>>> 44a20f0 (make use of set providers)
        }

        //                // step by step level reference
        //                if ($constantName === 'CODE_QUALITY') {
        //                    $rectorClasses = CodeQualityLevel::RULES;
        //                } elseif ($constantName === 'DEAD_CODE') {
        //                    $rectorClasses = DeadCodeLevel::RULES;
        //                } elseif ($constantName === 'TYPE_DECLARATION') {
        //                    $rectorClasses = TypeDeclarationLevel::RULES;
        //                } else {
        //                    $rectorClasses = $this->resolveRectorClassesFromSetFilePath($constantValue);
        //                }
        //
        //                $rectorSets[] = new RectorSet($constantName, $rectorClasses, $groupName);
        //            }
        //        }

        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }
<<<<<<< HEAD
=======

    //    /**
    //     * @return string[]
    //     */
    //    private function resolveRectorClassesFromSetFilePath(string $constantValue): array
    //    {
    //        $rectorClassesNames = $nodeFinder->find($nodes, function (Node $node): bool {
    //            if (! $node instanceof Name) {
    //                return false;
    //            }
    //
    //            return str_ends_with($node->toString(), 'Rector');
    //        });
    //
    //        $rectorClasses = [];
    //
    //        /** @var Name[] $rectorClassesNames */
    //        foreach ($rectorClassesNames as $rectorClassName) {
    //            $rectorClasses[] = $rectorClassName->toString();
    //        }
    //
    //        return $rectorClasses;
    //    }
>>>>>>> 44a20f0 (make use of set providers)
}
