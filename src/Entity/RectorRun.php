<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use JsonSerializable;
use Nette\Utils\Strings;
use Rector\Website\Exception\ShouldNotHappenException;
use Rector\Website\Utils\FileDiffCleaner;
use Rector\Website\Utils\StringsConverter;
use Rector\Website\ValueObject\AppliedRule;
use Symfony\Component\Uid\Uuid;

/**
 * @api used in templates
 */
final class RectorRun implements JsonSerializable
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
        private readonly Uuid $uuid,
        private string $content,
        private string $config,
        /** @var array<string, mixed> */
        private array $jsonResult = [],
        private string|null $fatalErrorMessage = null
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getContentDiff(): string
    {
        $fileDiff = $this->jsonResult['file_diffs'][0]['diff'] ?? null;
        if (is_string($fileDiff)) {
            $fileDiffCleaner = new FileDiffCleaner();
            return $fileDiffCleaner->clean($fileDiff);
        }

        return self::NO_CHANGE_CONTENT;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getConfig(): string
    {
        return $this->config;
    }

    public function isSuccessful(): bool
    {
        if ($this->fatalErrorMessage !== null) {
            return false;
        }

        if ($this->jsonResult === []) {
            return false;
        }

        if (! isset($this->jsonResult['errors'])) {
            return true;
        }

        /** @var mixed[] $errors */
        $errors = $this->jsonResult['errors'];

        return $errors === [];
    }

    public function getFatalErrorMessage(): ?string
    {
        return $this->fatalErrorMessage;
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        $jsonErrors = $this->jsonResult['errors'] ?? [];

        $errors = [];
        foreach ($jsonErrors as $jsonError) {
            // clear server paths for easier read
            $rawMessage = $jsonError['message'];
            // @see https://regex101.com/r/Viu6Tc/1
            $clearMessage = Strings::replace($rawMessage, '#\/(.*?)vendor/#i', '');
            $errors[] = $clearMessage;
        }

        return $errors;
    }

    public function setFatalErrorMessage(string $fatalErrorMessage): void
    {
        $this->fatalErrorMessage = $fatalErrorMessage;
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
     * @param mixed[] $jsonResult
     */
    public function setJsonResult(array $jsonResult): void
    {
        $this->jsonResult = $jsonResult;
    }

    public function hasRun(): bool
    {
        if ($this->fatalErrorMessage !== null) {
            return true;
        }

        return $this->jsonResult !== [];
    }

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
}
