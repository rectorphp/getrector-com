<?php

declare(strict_types=1);

namespace Rector\Website\Sets;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\NodeFinder;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;
use Rector\Set\Contract\SetListInterface;
use Rector\Set\ValueObject\SetList;
use Rector\Website\PhpParser\SimplePhpParser;
use Rector\Website\RuleFilter\ValueObject\RectorSet;
use Rector\Website\Utils\ClassNameResolver;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class RectorSetsTreeProvider
{
    /**
     * Cache to keep it fast
     * @var RectorSet[]
     */
    private array $rectorSets = [];

    public function __construct(
        private SimplePhpParser $simplePhpParser,
    ) {
    }

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

        $rectorSets = [];

        // @todo possibly cache or prebuild this?
        foreach ($this->findSetListFileInfos() as $setListFileInfo) {
            // no level sets, as deprecated
            if (str_contains($setListFileInfo->getRealPath(), 'Level')) {
                continue;
            }

            $setListClassName = $this->resolveClassName($setListFileInfo);
            $setListReflectionClass = new ReflectionClass($setListClassName);

            foreach ($setListReflectionClass->getConstants() as $constantName => $constantValue) {
                // skipped as internal
                if ($constantName === 'RECTOR_PRESET') {
                    continue;
                }

                $groupName = $this->resolveGroupName($setListReflectionClass, $constantName);

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

                $rectorSets[] = new RectorSet($constantName, $rectorClasses, $groupName);
            }
        }

        $this->rectorSets = $rectorSets;

        return $rectorSets;
    }

    /**
     * @return SplFileInfo[]
     */
    private function findSetListFileInfos(): array
    {
        // @todo possibly add to SetProvider to use directly without any this magic
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
     * @return class-string<SetListInterface>
     */
    private function resolveClassName(SplFileInfo $fileInfo): string
    {
        $className = ClassNameResolver::resolveFromFileContents($fileInfo->getContents(), $fileInfo->getRealPath());
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

    /**
     * @param ReflectionClass<SetListInterface> $setListReflectionClass
     */
    private function resolveGroupName(ReflectionClass $setListReflectionClass, string $constantName): string
    {
        if (str_starts_with($constantName, 'PHP_')) {
            return 'PHP';
        }

        if ($setListReflectionClass->getName() === SetList::class) {
            return 'Core';
        }

        // rector split package
        $match = Strings::match($setListReflectionClass->getName(), '#\\\\(?<name>[\w]+)SetList#');
        Assert::isArray($match);

        return $match['name'];
    }
}
