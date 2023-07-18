<?php

declare(strict_types=1);

namespace Rector\Website\Documentation;

use Rector\Website\ValueObject\DocumentationSection;

/**
 * @see \Rector\Website\Tests\Documentation\DocumentationMenuFactoryTest
 */
final class DocumentationMenuFactory
{
    /**
     * @return array<string, DocumentationSection[]>
     */
    public function create(): array
    {
        return [
            'Configuration' => [
                new DocumentationSection('import-names', 'Import Names'),
                new DocumentationSection('define-paths', 'Define Paths'),
                new DocumentationSection('set-lists', 'Set Lists'),
                new DocumentationSection('ignoring-rules-or-paths', 'Ignoring Rules or Paths'),
                new DocumentationSection('configured-rules', 'Configured Rules'),
                new DocumentationSection('static-reflection-and-autoload', 'Static Reflection And Autoload'),
                new DocumentationSection('config-configuration', 'Config Configuration'),
                new DocumentationSection('php-version-features', 'PHP Version Features'),
                new DocumentationSection('debugging', 'Debugging'),
            ],
            'Testing and CI' => [
                new DocumentationSection('cache-in-ci', 'Cache in CI'),
                new DocumentationSection('troubleshooting-parallel', 'Troubleshooting Parallel'),
                new DocumentationSection('reporting-issue-with-rector', 'Reporting Issue With Rector'),
            ],
            'Advanced' => [
                new DocumentationSection('how-rector-works', 'How Rector Works'),
                new DocumentationSection('custom-rule', 'Custom Rule'),
                new DocumentationSection('writing-tests-for-custom-rule', 'Writing Tests For Custom Rule'),
                new DocumentationSection('rules-overview', 'Rules Overview'),
                new DocumentationSection('creating-a-node-visitor', 'Creating Node Visitor'),
            ],
        ];
    }

    public function createSectionTitle(string $section): string
    {
        $sectionWords = str_replace('-', ' ', $section);
        $sectionWords = ucwords($sectionWords);

        return str_replace([' In', 'Ci'], [' in', 'CI'], $sectionWords);
    }
}
