<?php

declare(strict_types=1);

namespace Rector\Website\Sets;

use Rector\Website\Utils\ClassNameResolver;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Webmozart\Assert\Assert;

final class RectorSetsTreeFactory
{
    public function create()
    {
        // what set is this rules part of?
        foreach ($this->findSetListFileInfos() as $setListFileInfo) {
            $setListClassName = $this->resolveClassName($setListFileInfo);

            $setListReflectionClass = new \ReflectionClass($setListClassName);

            foreach ($setListReflectionClass->getConstants() as $constantName => $constantValue) {
                   dump($constantName, $constantValue);
            }
        }

        die;
    }

    /**
     * @return SplFileInfo[]
     */
    private function findSetListFileInfos(): array
    {
        $setsListFileInfos = Finder::create()
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

        return iterator_to_array($setsListFileInfos);
    }

    private function resolveClassName(SplFileInfo $fileInfo): string
    {
        $className = ClassNameResolver::resolveFromFileContents($fileInfo->getContents());
        Assert::string($className);
        Assert::classExists($className);

        return $className;
    }
}
