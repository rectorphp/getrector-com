<?php

declare(strict_types=1);

namespace Rector\Website\Sets;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeFinder;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\RuleFilter\ValueObject\RectorSet;
use Rector\Website\Utils\ClassNameResolver;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class RectorSetsTreeFactory
{
    public function __construct(
        private SimplePhpParser $simplePhpParser,
    ) {
    }

    /**
     * @return RectorSet[]
     */
    public function create(): array
    {
        $rectorSets = [];

        // what set is this rules part of?
        // @todo possibly cache or prebuild this?
        foreach ($this->findSetListFileInfos() as $setListFileInfo) {
            $setListClassName = $this->resolveClassName($setListFileInfo);
            $setListReflectionClass = new ReflectionClass($setListClassName);

            foreach ($setListReflectionClass->getConstants() as $constantName => $constantValue) {
                // meta sets, can be skipped
                if (str_contains($constantName, 'UP_TO_')) {
                    continue;
                }

                // step by step level reference
                if ($constantName === 'CODE_QUALITY') {
                    $rectorClasses = CodeQualityLevel::RULES;
                } elseif ($constantName === 'DEAD_CODE') {
                    $rectorClasses = DeadCodeLevel::RULES;
                } elseif ($constantName === 'TYPE_DECLARATION') {
                    $rectorClasses = TypeDeclarationLevel::RULES;
                } else {
                    $rectorClasses = $this->resolveRectorClassesFromSetFilePath($constantValue);
                }

                $rectorSets[] = new RectorSet($constantName, $rectorClasses);
            }
        }

        return $rectorSets;
    }

    /**
     * @return SplFileInfo[]
     */
    private function findSetListFileInfos(): array
    {
        $setsListFileIterator = Finder::create()
            ->files()
            ->in(__DIR__ . '/../../vendor/rector/rector/src/Set')
            ->in(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-doctrine/src/Set')
            ->in(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-symfony/src/Set')
            ->in(__DIR__ . '/../../vendor/rector/rector/vendor/rector/rector-phpunit/src/Set')
            // @todo add unofficial extensions as well
            ->name('*SetList.php')
            ->files()
            ->sortByName()
            ->getIterator();

        return iterator_to_array($setsListFileIterator);
    }

    /**
     * @return class-string
     */
    private function resolveClassName(SplFileInfo $fileInfo): string
    {
        $className = ClassNameResolver::resolveFromFileContents($fileInfo->getContents());
        Assert::string($className);
        Assert::classExists($className);

        return $className;
    }

    /**
     * @return string[]
     */
    private function resolveRectorClassesFromSetFilePath(string $constantValue): array
    {
        Assert::fileExists($constantValue);

        $nodes = $this->simplePhpParser->parseFile($constantValue);
        $nodeFinder = new NodeFinder();

        $rectorClassesNames = $nodeFinder->find($nodes, function (Node $node): bool {
            if (! $node instanceof Name) {
                return false;
            }

            return str_ends_with($node->toString(), 'Rector');
        });

        $rectorClasses = [];

        /** @var Name[] $rectorClassesNames */
        foreach ($rectorClassesNames as $rectorClassName) {
            $rectorClasses[] = $rectorClassName->toString();
        }

        return $rectorClasses;
    }
}
