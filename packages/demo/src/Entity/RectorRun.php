<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Nette\Utils\Strings;
use Rector\Website\Demo\Utils\FileDiffCleaner;
use Rector\Website\Demo\Validator\Constraint\PHPConstraint;
use Rector\Website\Exception\ShouldNotHappenException;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity
 */
class RectorRun implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var string
     */
    private const NO_CHANGE_CONTENT = '// no change';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="json")
     * @var mixed[]
     */
    private array $jsonResult = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $fatalErrorMessage = null;

    /**
     * @ORM\Column(type="text")
     */
    #[PHPConstraint]
    private string $config;

    /**
     * @ORM\Column(type="text")
     */
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

        return $this->jsonResult !== [];
    }

    public function getFatalErrorMessage(): ?string
    {
        return $this->fatalErrorMessage;
    }

    public function setFatalErrorMessage(string $fatalErrorMessage): void
    {
        $this->fatalErrorMessage = $fatalErrorMessage;
    }

    /**
     * @return string[]
     */
    public function getAppliedRules(): array
    {
        if ($this->jsonResult === []) {
            return [];
        }

        $result = $this->jsonResult['file_diffs'][0]['applied_rectors'] ?? [];
        return (array) $result;
    }

    /**
     * @return string[]
     */
    public function getAppliedShortRules(): array
    {
        $appliedShortRules = [];

        foreach ($this->getAppliedRules() as $appliedRule) {
            $appliedShortRules[] = $this->resolveShortRule($appliedRule);
        }

        return $appliedShortRules;
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

    private function resolveShortRule(string $rectorClass): string
    {
        $shortClassName = Strings::after($rectorClass, '\\', -1);

        if (! is_string($shortClassName)) {
            throw new ShouldNotHappenException();
        }

        return $shortClassName;
    }
}
