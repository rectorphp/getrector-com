<?php

declare(strict_types=1);

namespace App\RuleFilter\PhpParser\Printer;

use Nette\Utils\Strings;
use Override;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt;
use PhpParser\PrettyPrinter\Standard;

/**
 * @see \App\Tests\RuleFilter\PhpParser\RectorConfigStmtsPrinterTest
 */
final class RectorConfigStmtsPrinter extends Standard
{
    /**
     * @param Stmt[] $stmts
     */
    public function print(array $stmts): string
    {
        $contents = $this->prettyPrintFile($stmts);

        // add newline after configure() by convention
        $contents = Strings::replace($contents, '#configure\(\)#', 'configure()' . PHP_EOL . '    ');

        // indent array better
        $contents = Strings::replace($contents, '#\[\n(.*?)\n\]#ms', '[' . PHP_EOL . '    $1' . PHP_EOL . '    ]');

        return $contents . PHP_EOL;
    }

    /**
     * Print modern array with 1 item per line
     */
    #[Override]
    protected function pExpr_Array(Array_ $array): string
    {
        return '[' . $this->pCommaSeparatedMultiline($array->items, true) . $this->nl . ']';
    }
}
