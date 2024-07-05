<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\Printer;

use Nette\Utils\Strings;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * @see \App\Tests\RuleFilter\PhpParser\RectorConfigStmtsPrinterTest
 */
final class RectorConfigStmtsPrinter
{
    public function __construct(
        private readonly Standard $standard,
        private readonly Filesystem $filesystem,
    ) {
    }

    /**
     * @param Stmt[] $stmts
     */
    public function print(array $stmts): string
    {
        $printedConfiguration = $this->standard->prettyPrint($stmts);

        // add newline after configure() by convention
        $printedConfiguration = Strings::replace($printedConfiguration, '#configure\(\)#', 'configure()' . PHP_EOL);
        return $this->applyCodingStandards($printedConfiguration);
    }

    private function applyCodingStandards(string $printedConfiguration): string
    {
        $rootDirectory = __DIR__ . '/../../../..';

        // use temporary safe path
        $temporaryFilePath = sys_get_temp_dir() . '/web_ecs_storage/temp-ecs-file.php';

        $this->filesystem->dumpFile($temporaryFilePath, '<?php ' . PHP_EOL . PHP_EOL . $printedConfiguration);
        $process = new Process([PHP_BINARY, 'vendor/bin/ecs', 'check', $temporaryFilePath, '--fix'], $rootDirectory);
        $process->mustRun();

        $fixedFileConfiguration = $this->filesystem->readFile($temporaryFilePath);
        $this->filesystem->remove($temporaryFilePath);

        return $fixedFileConfiguration;
    }
}
