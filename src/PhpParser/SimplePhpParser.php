<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Rector\Website\Exception\ShouldNotHappenException;

final class SimplePhpParser
{
    private Parser $phpParser;

    private NodeTraverser $nodeTraverser;

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::ONLY_PHP7);

        $this->nodeTraverser = new NodeTraverser();
        $this->nodeTraverser->addVisitor(new NameResolver());

        $this->nodeFinder = new NodeFinder();
    }

    /**
     * @api tests
     * @return Node[]
     */
    public function parseFile(string $filePath): array
    {
        $fileContent = FileSystem::read($filePath);
        return $this->parseString($fileContent);
    }

    /**
     * @api tests
     */
    public function parseFileToClass(string $filePath): Class_
    {
        $fileContent = FileSystem::read($filePath);
        $nodes = $this->parseString($fileContent);

        $foundClass = $this->nodeFinder->findFirstInstanceOf($nodes, Class_::class);
        if (! $foundClass instanceof Class_) {
            throw new ShouldNotHappenException();
        }

        return $foundClass;
    }

    /**
     * @return Node[]
     */
    public function parseString(string $fileContent): array
    {
        $fileContent = $this->ensureFileContentsHasOpeningTag($fileContent);

        $nodes = $this->phpParser->parse($fileContent);
        if ($nodes === null) {
            return [];
        }

        return $this->nodeTraverser->traverse($nodes);
    }

    private function ensureFileContentsHasOpeningTag(string $fileContent): string
    {
        if (! str_starts_with(trim($fileContent), '<?php')) {
            // prepend with PHP opening tag to make parse PHP code
            return '<?php ' . $fileContent;
        }

        return $fileContent;
    }
}
