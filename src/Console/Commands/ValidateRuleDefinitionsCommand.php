<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\FileSystem\RectorFinder;
use Illuminate\Console\Command;

final class ValidateRuleDefinitionsCommand extends Command
{
    private const int MIN_RULE_LIMIT = 655;

    protected $signature = 'app:validate-rule-definitions';

    protected $description = 'Validate Rector rule definitions';

    public function handle(RectorFinder $rectorFinder): int
    {
        $this->line('Validating core Rector rule definitions');

        $coreRuleMetadatas = $rectorFinder->findCore();
        $this->info(sprintf('Found %d valid core Rector rules', count($coreRuleMetadatas)));

        $communityRuleMetadatas = $rectorFinder->findCommunity();
        $this->info(sprintf('Found %d valid community Rector rules', count($communityRuleMetadatas)));

        $ruleMetadatas = array_merge($coreRuleMetadatas, $communityRuleMetadatas);

        $isValid = true;
        foreach ($ruleMetadatas as $ruleMetadata) {
            if ($ruleMetadata->getDescription() === '') {
                $isValid = false;
                $this->error(
                    sprintf(
                        'Rule "%s" is missing description. Fill it first to enable rule search',
                        $ruleMetadata->getRuleShortClass()
                    )
                );
            }

            if ($ruleMetadata->getCodeSamples() === []) {
                $this->error(
                    sprintf(
                        'Rule "%s" is missing code samples. Fill it first to enable rule search',
                        $ruleMetadata->getRuleShortClass()
                    )
                );
                $isValid = false;
            }
        }

        if ($isValid === false) {
            return self::FAILURE;
        }

        if (count($ruleMetadatas) < self::MIN_RULE_LIMIT) {
            $this->error(sprintf(
                'Only %d rule definitions found. Make sure all are loaded and above %d',
                count($ruleMetadatas),
                self::MIN_RULE_LIMIT
            ));

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
