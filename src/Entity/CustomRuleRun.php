<?php

declare(strict_types=1);

namespace Rector\Website\Entity;

use Symfony\Component\Uid\Uuid;

final class CustomRuleRun extends AbstractRectorRun
{
    public function __construct(
        readonly Uuid $uuid,
        readonly string $customRule,
        readonly string $content,
        /** @var array<string, mixed> */
        array $jsonResult = [],
        string|null $fatalErrorMessage = null
    ) {
        parent::__construct($uuid, $content, $jsonResult, $fatalErrorMessage);
    }

    /**
     * @return array{
     *     uuid: string,
     *     config: string,
     *     json_result: mixed[],
     *     fatal_error_message: string|null
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'uuid' => $this->uuid->jsonSerialize(),
            'content' => $this->content,
            'json_result' => $this->jsonResult,
            'fatal_error_message' => $this->fatalErrorMessage,
        ];
    }
}
