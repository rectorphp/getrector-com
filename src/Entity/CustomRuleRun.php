<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Nette\Utils\FileSystem;
use Symfony\Component\Uid\Uuid;

final class CustomRuleRun extends AbstractRectorRun
{
    /**
     * @param array<string, mixed> $jsonResult
     */
    public function __construct(
        Uuid $uuid,
        readonly string $rectorRule,
        string $content,
        array $jsonResult = [],
        string|null $fatalErrorMessage = null
    ) {
        parent::__construct($uuid, $content, $jsonResult, $fatalErrorMessage);
    }

    public function getRectorRule(): string
    {
        return $this->rectorRule;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'rector_rule' => $this->rectorRule,
            'content' => $this->content,
            'json_result' => $this->jsonResult,
            'fatal_error_message' => $this->fatalErrorMessage,
        ];
    }

    public static function createEmpty(): self
    {
        // default values
        $rectorRule = FileSystem::read(__DIR__ . '/../../resources/default-form-data/custom-rule/CustomRuleRector.php');
        $phpSnippet = FileSystem::read(__DIR__ . '/../../resources/default-form-data/custom-rule/php-snippet.php');

        return new self(Uuid::v4(), $rectorRule, $phpSnippet);
    }
}
