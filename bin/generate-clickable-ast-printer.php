<?php

declare(strict_types=1);
use Illuminate\Container\Container;

use Nette\Utils\FileSystem;
use PhpParser\PrettyPrinter\Standard;
use Rector\Website\Utils\ClickablePrinterBuilder;

require __DIR__ . '/../vendor/autoload.php';

// simple script to generate clickable AST printer
// use in https://getrector.com/ast

$container = new Container();

/** @var ClickablePrinterBuilder $clickablePrinterBuilder */
$clickablePrinterBuilder = $container->make(ClickablePrinterBuilder::class);

$clickableAstPrinterClass = $clickablePrinterBuilder->buildClass(
    __DIR__ . '/../vendor/nikic/php-parser/lib/PhpParser/PrettyPrinter/Standard.php'
);

$fileStmts = $clickablePrinterBuilder->buildFileStmts($clickableAstPrinterClass);

$standardPrinter = new Standard();
$printedClass = $standardPrinter->prettyPrintFile($fileStmts);

$targetFilePath = getcwd() . '/src/PhpParser/ClickablePrinter.php';
FileSystem::write($targetFilePath, $printedClass);

echo sprintf('Done and generated to%s%s', PHP_EOL, $targetFilePath) . PHP_EOL;
