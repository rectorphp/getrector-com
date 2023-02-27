<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRector\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SomeCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $someValue = $input->getArgument('some_argument');

        return self::SUCCESS;
    }
}

?>
-----
<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\SymfonyCommandToLaravelCommandRector\Fixture;

final class SomeCommand extends \Illuminate\Console\Command
{
    public function handle(): int
    {
        $someValue = $input->argument('some_argument');
        return self::SUCCESS;
    }
}

?>
