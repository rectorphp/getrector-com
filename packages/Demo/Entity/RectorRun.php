<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Nette\Utils\Strings;
use Rector\Website\Demo\Utils\FileDiffCleaner;
use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Rector\Website\Demo\ValueObject\AppliedRule;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symplify\PackageBuilder\Strings\StringFormatConverter;

#[Entity]
class RectorRun implements TimestampableInterface
{
    use TimestampableTrait;

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

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $id;

    /**
     * @var mixed[]
     */
    #[Column(type: 'json')]
    private array $jsonResult = [];

    #[Column(type: 'text', nullable: true)]
    private ?string $fatalErrorMessage = null;

    #[Column(type: 'text')]
    #[PHPConstraint]
    private string $config;

    #[Column(type: 'text')]
    #[PHPConstraint]
    private string $content;

    public function getId(): Uuid
    {
        return $this->id;
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

    /**
     * @return mixed[]
     */
    public function getJsonResult(): array
    {
        return $this->jsonResult;
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

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setConfig(string $config): void
    {
        $this->config = $config;
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

    public function setId(Uuid $uuid): void
    {
        $this->id = $uuid;
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

        $stringsConverter = new StringFormatConverter();
        $underscoredName = $stringsConverter->camelCaseToUnderscore($baseFilename);

        return $underscoredName . '.php.inc';
    }
}
