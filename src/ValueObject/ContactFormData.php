<?php

declare(strict_types=1);

namespace Rector\Website\ValueObject;

final class ContactFormData
{
    public function __construct(
        private string $name,
        private string $email,
        private int $projectSize,
        private string $framework,
        private int $currentPhpVersion,
        private ?int $targetPhpVersion,
        private string $message,
    ) {
    }
}
