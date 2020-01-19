<?php

declare(strict_types=1);

namespace Rector\Website\Lint;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Exception\LintingException;
use Symfony\Component\Process\Process;

final class PHPFileLinter
{
    public function checkContentSyntax(string $content): void
    {
        $this->checkOpeningPhpTag($content);

        // see https://stackoverflow.com/a/18243142/1348344
        $process = new Process(['echo', $content, '|', 'php', '-l']);
        $process->run();

        if ($process->isSuccessful()) {
            return;
        }

        throw new LintingException($process->getOutput(), (int) $process->getExitCode());
    }

    public function checkFileSyntax(string $absoluteFilePath): void
    {
        $content = FileSystem::read($absoluteFilePath);

        $this->checkOpeningPhpTag($content);

        $process = new Process(['php', '-l', $absoluteFilePath]);
        $process->run();

        if ($process->isSuccessful()) {
            return;
        }

        throw new LintingException($process->getOutput(), (int) $process->getExitCode());
    }

    private function checkOpeningPhpTag(string $content): void
    {
        if (Strings::startsWith($content, '<?php')) {
            return;
        }

        throw new LintingException('Complete opening php tag "<?php"');
    }
}
