<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use JsonSerializable;
use Nette\Utils\Strings;
use Rector\Website\Enum\Comment;
use Rector\Website\Utils\FileDiffCleaner;
use Symfony\Component\Uid\Uuid;

abstract class AbstractRectorRun implements JsonSerializable
{
    public function __construct(
        protected readonly Uuid $uuid,
        protected readonly string $content,
        protected readonly string $runnablePhp,
        /** @var array<string, mixed> */
        protected array $jsonResult = [],
        protected string|null $fatalErrorMessage = null
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

        return Comment::NO_CHANGE_CONTENT;
    }

    public function getRunnablePhp(): string
    {
        return $this->runnablePhp;
    }

    public function getContent(): string
    {
        return $this->content;
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
}
