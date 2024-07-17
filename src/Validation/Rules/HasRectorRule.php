<?php

declare(strict_types=1);

namespace App\Validation\Rules;

use App\Exception\ShouldNotHappenException;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Nette\Utils\Random;
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

            $identifier = Random::generate(20);
            $configFilePath = sys_get_temp_dir() . '/temp-' . $identifier . '-rector-config.php';
            $filesystem->dumpFile($configFilePath, $value);

            try {
                $rectorContainer = $this->createFromConfigs([$configFilePath]);
            } catch (ShouldNotHappenException) {
                $fail('PHP config should have valid method name, you may have typo');
                return;
            }

            $rectors = $rectorContainer->tagged(RectorInterface::class);

            // remove no longer used
            $filesystem->remove($configFilePath);

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
            try {
                $rectorConfig->import($configFile);
            } catch (\Throwable) {
                throw new ShouldNotHappenException();
            }
        }

        $rectorConfig->boot();

        return $rectorConfig;
    }
}
