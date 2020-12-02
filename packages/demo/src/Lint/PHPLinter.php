<?php

declare(strict_types=1);

namespace Rector\Website\Demo\Lint;

use Nette\Utils\Strings;
use Rector\Website\Demo\Exception\Linter\MissingPHPOpeningTagException;
use Rector\Website\Demo\Exception\LintingException;
use Symfony\Component\Process\Process;
use Symplify\SmartFileSystem\SmartFileSystem;

final class PHPLinter
{
    /**
     * @see https://regex101.com/r/GzUnSz/1
     * @var string
     */
    private const OPENING_PHP_TAG_REGEX = '#(\s+)?\<\?php#';

    public function __construct(private SmartFileSystem $smartFileSystem)
    {
    }

    public function checkContentSyntax(string $content): void
    {
        $this->checkOpeningPhpTag($content);

        $fileName = md5($content);
        $filePath = sys_get_temp_dir() . '/temp/' . $fileName;
        $this->smartFileSystem->dumpFile($filePath, $content);

        $process = new Process(['php', '-l', $filePath]);
        $process->run();

        if ($process->isSuccessful()) {
            return;
        }

        throw new LintingException($process->getErrorOutput(), (int) $process->getExitCode());
    }

    private function checkOpeningPhpTag(string $content): void
    {
        if (Strings::match($content, self::OPENING_PHP_TAG_REGEX)) {
            return;
        }

        throw new MissingPHPOpeningTagException();
    }
}
