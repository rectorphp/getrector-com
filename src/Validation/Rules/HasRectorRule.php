<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PhpParser\Error;
use Rector\Config\RectorConfig;
use Rector\Contract\Rector\RectorInterface;
use Rector\DependencyInjection\LazyContainerFactory;
use Rector\Rector\AbstractRector;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @see \App\Tests\Validator\Rules\HasRectorRule\HasRectorRuleTest
 */
final class HasRectorRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // dummy check for custom rule request
        if (str_contains($value, AbstractRector::class)) {
            return;
        }

        try {
            $filesystem = new Filesystem();

            $configFilePath = sys_get_temp_dir() . '/temp-' . bin2hex(random_bytes(16)) . '-rector-config.php';
            $filesystem->dumpFile($configFilePath, $value);

            $rectorContainer = $this->createFromConfigs([$configFilePath]);
            $rectors = $rectorContainer->tagged(RectorInterface::class);

            // remove no longer used
            unlink($configFilePath);

            if ((is_countable($rectors) ? count($rectors) : 0) > 0) {
                return;
            }

            $fail('PHP config should include at least 1 rector rule');
        } catch (Error $error) {
            $fail(sprintf('PHP code is invalid: %s', $error->getMessage()));
        }
    }

    /**
     * @todo extract to bridge
     * @param string[] $configFiles
     */
    private function createFromConfigs(array $configFiles): RectorConfig
    {
        $lazyContainerFactory = new LazyContainerFactory();
        $rectorConfig = $lazyContainerFactory->create();

        foreach ($configFiles as $configFile) {
            $rectorConfig->import($configFile);
        }

        $rectorConfig->boot();

        return $rectorConfig;
    }
}
