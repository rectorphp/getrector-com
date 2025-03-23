<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\FileSystem\RectorFinder;
use Illuminate\Console\Command;

final class ValidateRuleDefinitionsCommand extends Command
{
    protected $signature = 'app:validate-rule-definitions';

    protected $description = 'Validate Rector rule definitions';

    public function handle(RectorFinder $rectorFinder): int
    {
        $this->line('Validating core Rector rule definitions');

        $coreRuleMetadatas = $rectorFinder->findCore();
        $this->info(sprintf('Found %d valid core Rector rules', count($coreRuleMetadatas)));

        $communityRuleMetadatas = $rectorFinder->findCommunity();
        $this->info(sprintf('Found %d valid community Rector rules', count($communityRuleMetadatas)));

        //        foreach ($ruleMetadatas as $ruleMetadata) {
        //            $isValid = true;
        //            if ($ruleMetadata->getDescription() === '') {
        //                $isValid = false;
        //                $symfonyStyle->error(sprintf('Rule "%s" is missing description. Fill it first to enable rule search', $ruleMetadata->getRuleShortClass()));
        //            }
        //
        //            if ($ruleMetadata->getCodeSamples() === []) {
        //                $symfonyStyle->error(sprintf('Rule "%s" is missing code samples. Fill it first to enable rule search', $ruleMetadata->getRuleShortClass()));
        //                $isValid = false;
        //            }
        //        }

        return self::SUCCESS;
    }
}
