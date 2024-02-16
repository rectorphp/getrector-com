<?php

declare(strict_types=1);

namespace Rector\Website\PhpParser;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;

final class SimplePhpParser
{
    private Parser $phpParser;

    private NodeTraverser $nodeTraverser;

    public function __construct()
    {
        $parserFactory = new ParserFactory();
        $this->phpParser = $parserFactory->create(ParserFactory::ONLY_PHP7);

        $this->nodeTraverser = new NodeTraverser();
        $this->nodeTraverser->addVisitor(new NameResolver());
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
