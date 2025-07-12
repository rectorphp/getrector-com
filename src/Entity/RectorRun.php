<?php

declare(strict_types=1);

namespace App\Entity;

use App\Exception\ShouldNotHappenException;
use App\Utils\ClassNameResolver;
use App\Utils\StringsConverter;
use App\ValueObject\AppliedRule;
use Nette\Utils\FileSystem;
use Symfony\Component\Uid\Uuid;

final class RectorRun extends AbstractRectorRun
{
    private const string DEFAULT_FILE_NAME = 'demo_fixture';

    /**
     * @return AppliedRule[]
     */
    public function getAppliedRules(): array
    {
        if ($this->jsonResult === []) {
            return [];
        }

        $appliedRectors = $this->jsonResult['file_diffs'][0]['applied_rectors'] ?? [];
        $appliedRectors = (array) $appliedRectors;

        $appliedRules = [];
        foreach ($appliedRectors as $appliedRector) {
            $appliedRules[] = new AppliedRule($appliedRector);
        }

        return $appliedRules;
    }

    /**
     * @api used in blade
     */
    public function canCreateFixture(): bool
    {
        return count($this->getAppliedRules()) === 1;
    }

    public function getExpectedRectorTestNamespace(): string
    {
        $onlyAppliedRule = $this->getAppliedRules()[0] ?? null;
        if (! $onlyAppliedRule instanceof AppliedRule) {
            throw new ShouldNotHappenException('Single applied rule is required to make a test fixture link');
        }

        return $onlyAppliedRule->getTestFixtureNamespace();
    }

    public function getExpectedRectorTestPath(): string
    {
        $onlyAppliedRule = $this->getAppliedRules()[0] ?? null;
        if (! $onlyAppliedRule instanceof AppliedRule) {
            throw new ShouldNotHappenException('Test can be create only if exactly 1 rule is responsible');
        }

        return $onlyAppliedRule->getTestFixtureDirectoryPath();
    }

    public function getRectorShortClass(): string
    {
        $onlyAppliedRule = $this->getAppliedRules()[0] ?? null;
        if (! $onlyAppliedRule instanceof AppliedRule) {
            throw new ShouldNotHappenException('Single applied rule is required to make a test fixture link');
        }

        return $onlyAppliedRule->getShortClass();
    }

    public function getFixtureFileName(): string
    {
        $shortClassName = ClassNameResolver::resolveShortClassName($this->content);
        $baseFilename = $shortClassName ?? self::DEFAULT_FILE_NAME;

        $stringsConverter = new StringsConverter();
        $underscoredName = $stringsConverter->camelCaseToGlue($baseFilename, '_');

        return $underscoredName . '.php.inc';
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'content' => $this->content,
            'config' => $this->runnablePhp,
            'json_result' => $this->jsonResult,
            'fatal_error_message' => $this->fatalErrorMessage,
        ];
    }

    public static function createEmpty(): self
    {
        // default values
        $fileContents = FileSystem::read(__DIR__ . '/../../resources/default-form-data/demo/DemoFile.php');
        $configContents = FileSystem::read(__DIR__ . '/../../resources/default-form-data/demo/demo-config.php');

        return new self(Uuid::v4(), $fileContents, $configContents);
    }
}
