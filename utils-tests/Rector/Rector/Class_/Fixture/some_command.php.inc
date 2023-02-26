<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\Fixture;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SomeCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return self::SUCCESS;
    }
}

?>
-----
<?php

namespace Rector\Website\Utils\Tests\Rector\Rector\Class_\Fixture;

final class SomeCommand extends \Illuminate\Console\Command
{
    public function handle(): int
    {
        return self::SUCCESS;
    }
}

?>
