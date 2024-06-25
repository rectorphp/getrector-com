<?php

declare(strict_types=1);

namespace App\Sets;

use App\PhpParser\SimplePhpParser;
use App\RuleFilter\ValueObject\RectorSet;
use Rector\Doctrine\Set\SetProvider\DoctrineSetProvider;
use Rector\PHPUnit\Set\SetProvider\PHPUnitSetProvider;
use Rector\Set\Contract\SetInterface;
use Rector\Set\SetProvider\CoreSetProvider;
use Rector\Symfony\Set\SetProvider\SymfonySetProvider;

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

                new RectorSet();
                // SimplePhpParser $simplePhpParser
            }
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
}
