<?php

declare(strict_types=1);

namespace Rector\Website\Lint;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Exception\LintingException;
use Symfony\Component\Process\Process;

final class PHPFileLinter
{
    public function checkFileSyntax(string $absoluteFilePath): void
    {
        $this->checkOpeningPhpTag($absoluteFilePath);

        $process = new Process(['php', '-l', $absoluteFilePath]);
        $process->run();

        if ($process->isSuccessful()) {
            return;
        }

        throw new LintingException($process->getOutput(), (int) $process->getExitCode());
    }

    private function checkOpeningPhpTag(string $absoluteFilePath): void
    {
        $content = FileSystem::read($absoluteFilePath);

        if (! Strings::startsWith($content, '<?php')) {
            throw new LintingException('Opening php tag "<?php" is missing');
        }
    }
}
