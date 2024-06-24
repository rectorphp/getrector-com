<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Utils\StringsConverter;
use Rector\Website\ValueObject\AppliedRule;
use Symfony\Component\Uid\Uuid;

final class RectorRun extends AbstractRectorRun
{
    /**
     * @var string
     */
    public const NO_CHANGE_CONTENT = '// no change';

    /**
     * @see https://regex101.com/r/13A0W9/1
     * @var string
     */
    private const CLASS_NAME_REGEX = '#class\s+(?<' . self::PART_CLASS_NAME . '>\w+)#';

    /**
     * @var string
     */
    private const PART_CLASS_NAME = 'class_name';

    /**
     * @var string
     */
    private const DEFAULT_FILE_NAME = 'demo_fixture';

    public function __construct(
        Uuid $uuid,
        string $content,
        private readonly string $config,
        /** @var array<string, mixed> */
        array $jsonResult = [],
        string|null $fatalErrorMessage = null
    ) {
        parent::__construct(
            $uuid,
            $content,
            $jsonResult,
            $fatalErrorMessage
        );
    }

    public function getConfig(): string
    {
        return $this->config;
    }

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
        $matches = Strings::match($this->content, self::CLASS_NAME_REGEX);

        $baseFilename = $matches[self::PART_CLASS_NAME] ?? self::DEFAULT_FILE_NAME;

        $stringsConverter = new StringsConverter();
        $underscoredName = $stringsConverter->camelCaseToGlue($baseFilename, '_');

        return $underscoredName . '.php.inc';
    }

    /**
     * @return array{uuid: string, content: string, config: string, json_result: mixed[], fatal_error_message: string|null}
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'content' => $this->content,
            'config' => $this->config,
            'json_result' => $this->jsonResult,
            'fatal_error_message' => $this->fatalErrorMessage,
        ];
    }

    public static function createEmpty(): self
    {
        // default values
        $fileContents = FileSystem::read(__DIR__ . '/../../resources/demo/DemoFile.php');
        $configContents = FileSystem::read(__DIR__ . '/../../resources/demo/demo-config.php');

        return new self(Uuid::v4(), $fileContents, $configContents);
    }
}
