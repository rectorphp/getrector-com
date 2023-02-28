<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRector\Fixture;

use Symfony\Component\Console\Command\Command;

final class ConfigureToSignature extends Command
{
    protected function configure(): void
    {
        $this->setName('app:some-name');

        $this->addArgument('some_argument');
        $this->addOption('some-option');
    }
}

?>
-----
<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRector\Fixture;

final class ConfigureToSignature extends \Illuminate\Console\Command
{
    protected $signature = 'app:some-name';
    protected function configure(): void
    {
        $this->setName('app:some-name');

        $this->addArgument('some_argument');
        $this->addOption('some-option');
    }
}

?>
