<?php

declare(strict_types=1);

namespace App\Tests\RuleFilter\PhpParser;

use App\RuleFilter\PhpParser\NodeFactory\RectorConfigFactory;
use App\RuleFilter\PhpParser\Printer\RectorConfigStmtsPrinter;
use App\Tests\AbstractTestCase;
use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;

final class RectorConfigStmtsPrinterTest extends AbstractTestCase
{
    private RectorConfigFactory $rectorConfigFactory;

    private RectorConfigStmtsPrinter $rectorConfigStmtsPrinter;

    protected function setUp(): void
    {
        $this->rectorConfigFactory = $this->make(RectorConfigFactory::class);
        $this->rectorConfigStmtsPrinter = $this->make(RectorConfigStmtsPrinter::class);
    }

    public function test(): void
    {
        $configStmts = $this->rectorConfigFactory->createNormal(Php4ConstructorRector::class);

        $printedConfig = $this->rectorConfigStmtsPrinter->print($configStmts);
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/expected-config.php', $printedConfig);
    }
}
