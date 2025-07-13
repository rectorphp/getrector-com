<?php

declare(strict_types=1);

namespace App\Tests\RuleFilter\PhpParser;

use App\RuleFilter\PhpParser\NodeFactory\RectorConfigFactory;
use App\RuleFilter\PhpParser\Printer\RectorConfigStmtsPrinter;
use App\Tests\AbstractTestCase;
use Override;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;

final class RectorConfigStmtsPrinterTest extends AbstractTestCase
{
    private RectorConfigFactory $rectorConfigFactory;

    private RectorConfigStmtsPrinter $rectorConfigStmtsPrinter;

    #[Override]
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

    public function testConfigured(): void
    {
        $configurationArray = new Array_([
            new ArrayItem(new ConstFetch(new Name('false')), new ClassConstFetch(new Name('self'), new Identifier(
                'ONLY_DIRECT_ASSIGN'
            ))),
        ]);

        $configStmts = $this->rectorConfigFactory->createConfigured(
            SimplifyUselessVariableRector::class,
            $configurationArray
        );

        $printedConfig = $this->rectorConfigStmtsPrinter->print($configStmts);
        $this->assertStringEqualsFile(__DIR__ . '/Fixture/expected-configured-config.php', $printedConfig);
    }
}
