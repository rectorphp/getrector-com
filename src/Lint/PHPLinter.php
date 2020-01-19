<?php

declare(strict_types=1);

namespace Rector\Website\Lint;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Exception\LintingException;
use Symfony\Component\Process\Process;

final class PHPLinter
{
    public function checkContentSyntax(string $content): void
    {
        $this->checkOpeningPhpTag($content);

        $fileName = md5($content);
        $filePath = sys_get_temp_dir() . '/temp/' . $fileName;
        FileSystem::write($filePath, $content);

        $process = new Process(['php', '-l', $filePath]);
        $process->run();

        if ($process->isSuccessful()) {
            return;
        }

        throw new LintingException($process->getErrorOutput(), (int) $process->getExitCode());
    }

    private function checkOpeningPhpTag(string $content): void
    {
        if (Strings::match($content, '#(\s+)?\<\?php#')) {
            return;
        }

        throw new MissingPHPOpeningTagException();
    }
}
