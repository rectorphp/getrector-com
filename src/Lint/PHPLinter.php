<?php

declare(strict_types=1);

namespace Rector\Website\Lint;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Exception\Linter\ForbiddenPHPFunctionException;
use Rector\Website\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Exception\LintingException;
use Symfony\Component\Process\Process;

final class PHPLinter
{
    public function checkContentSyntax(string $content): void
    {
        $this->checkOpeningPhpTag($content);
        $this->checkForbiddenFunctions($content);

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

    private function checkForbiddenFunctions(string $content): void
    {
        $forbiddenFunctions = [
            'exec',
            'passthru',
            'shell_exec',
            'system',
            'proc_open',
            'popen',
            'curl_exec',
            'curl_multi_exec',
            'parse_ini_file',
            'show_source',
        ];

        // https://regex101.com/r/4in3xJ/2
        $pattern = sprintf('#^(?<function>%s)\s*\(#mi', implode('|', $forbiddenFunctions));
        $match = Strings::match($content, $pattern);

        if (isset($match['function'])) {
            throw new ForbiddenPHPFunctionException($match['function']);
        }
    }

    private function checkOpeningPhpTag(string $content): void
    {
        if (Strings::match($content, '#(\s+)?\<\?php#')) {
            return;
        }

        throw new MissingPHPOpeningTagException();
    }
}
