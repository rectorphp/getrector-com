<?php

declare(strict_types=1);

namespace App\Documentation;

use App\Controller\FindRuleController;

/**
 * @see \App\Tests\Documentation\DocumentationMenuFactoryTest
 */
final class DocumentationMenuFactory
{
    public function __construct(
        private DocumentationMenuItemFactory $documentationMenuItemFactory
    ) {
    }

    /**
     * @return array<string, DocumentationMenuItem[]>
     */
    public function create(): array
    {
        return [
            'First Steps' => [
                $this->documentationMenuItemFactory->createSection('integration-to-new-project', 'New Project', true),
                $this->documentationMenuItemFactory->createSection('define-paths', 'Define Paths'),
                $this->documentationMenuItemFactory->createSection('set-lists', 'Set Lists'),
                $this->documentationMenuItemFactory->createInternalLink(FindRuleController::class, 'Find Rules'),
                $this->documentationMenuItemFactory->createSection(
                    'ignoring-rules-or-paths',
                    'Ignoring Rules or Paths'
                ),
                $this->documentationMenuItemFactory->createSection('import-names', 'Import Names'),
                $this->documentationMenuItemFactory->createSection('configured-rules', 'Configured Rules'),
            ],
            'Configuration' => [
                $this->documentationMenuItemFactory->createSection(
                    'static-reflection-and-autoload',
                    'Static Reflection And Autoload'
                ),
                $this->documentationMenuItemFactory->createSection('config-configuration', 'Config Configuration'),
                $this->documentationMenuItemFactory->createSection('php-version-features', 'PHP Version Features'),
                $this->documentationMenuItemFactory->createSection('commands', 'Commands', true),
                $this->documentationMenuItemFactory->createSection('team-tools', 'Team Tools', true),
            ],
            'Testing and CI' => [
                $this->documentationMenuItemFactory->createSection('cache-in-ci', 'Cache in CI'),
                $this->documentationMenuItemFactory->createSection('debugging', 'Debugging'),
                $this->documentationMenuItemFactory->createSection(
                    'troubleshooting-parallel',
                    'Troubleshooting Parallel'
                ),
                $this->documentationMenuItemFactory->createSection(
                    'reporting-issue-with-rector',
                    'Reporting Issue With Rector'
                ),
            ],
            'Advanced' => [
                $this->documentationMenuItemFactory->createSection('how-rector-works', 'How Rector Works'),
                $this->documentationMenuItemFactory->createSection('custom-rule', 'Custom Rule'),
                $this->documentationMenuItemFactory->createSection(
                    'writing-tests-for-custom-rule',
                    'Writing Tests For Custom Rule'
                ),
                $this->documentationMenuItemFactory->createSection('rules-overview', 'Rules Overview'),
                $this->documentationMenuItemFactory->createSection('creating-a-node-visitor', 'Creating Node Visitor'),
                $this->documentationMenuItemFactory->createSection('how-to-run-on-php-53', 'Run on PHP 5.3', true),
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
