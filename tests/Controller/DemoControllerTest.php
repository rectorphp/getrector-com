<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\Demo\ProcessDemoFormController;
use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Config\RectorConfig;

final class DemoControllerTest extends AbstractTestCase
{
    /**
     * @var string
     */
    private const PHP_CONTENTS = 'php_contents';

    /**
     * @var string
     */
    private const RUNNABLE_CONTENTS = 'runnable_contents';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * @param array<string, string[]> $expectedInvalidKeysWithMessages
     */
    #[DataProvider('provideTestFormSubmitData')]
    public function testFormSubmit(
        string $phpContentsData,
        string $runnableContentsData,
        array $expectedInvalidKeysWithMessages
    ): void {
        $postUrl = action(ProcessDemoFormController::class);

        $testResponse = $this->post($postUrl, [
            self::PHP_CONTENTS => $phpContentsData,
            self::RUNNABLE_CONTENTS => $runnableContentsData,
        ]);

        $this->assertTrue($testResponse->isRedirect());

        if ($expectedInvalidKeysWithMessages !== []) {
            $this->assertFalse($testResponse->isSuccessful());

            // assert what inputs are invalid => error message
            $testResponse->assertInvalid($expectedInvalidKeysWithMessages);
        }
    }

    public static function provideTestFormSubmitData(): Iterator
    {
        // Send empty form
        yield ['', '', [
            self::PHP_CONTENTS => 'The php contents field is required.',
            self::RUNNABLE_CONTENTS => 'The runnable contents field is required.',
        ]];

        // missing php open tag
        yield ['failed', 'services:', [
            self::PHP_CONTENTS => 'PHP code is invalid: Missing PHP opening tag',
            self::RUNNABLE_CONTENTS => 'PHP code is invalid: Missing PHP opening tag',
        ]];

        // Invalid PHP syntax (missing semicolon in code box)
        yield ['<?php print $x', '', [
            self::PHP_CONTENTS => 'PHP code is invalid: Syntax error, unexpected EOF on line 1',
            self::RUNNABLE_CONTENTS => 'The runnable contents field is required.',
        ]];

        // Invalid PHP syntax (missing semicolon in config box)
        yield ['<?php print $x; ?>', '<?php return static function() {}', [
            self::RUNNABLE_CONTENTS => "PHP code is invalid: Syntax error, unexpected EOF, expecting ';' on line 1",
        ]];

        // Add dangerous exec() func call
        yield ['<?php echo "test"; ?>', '<?php exec("dangerous command"); ?>', [
            self::RUNNABLE_CONTENTS => 'PHP config should not include func call',
        ]];

        yield ['<?php echo "test"; ?>', '<?php `dangerous command`; ?>', [
            self::RUNNABLE_CONTENTS => 'PHP config should not include execution operator',
        ]];

        yield ['<?php echo "test"; ?>', '<?php include "index.php"; ?>', [
            self::RUNNABLE_CONTENTS => 'PHP config should not include include/require usage',
        ]];

        // Add no rule in config
        yield ['<?php echo "test"; ?>', '<?php return ' . RectorConfig::class . '::configure()->withImportNames(); ?>', [
            self::RUNNABLE_CONTENTS => 'PHP config should include at least 1 rector rule',
        ]];

        // valid PHP syntaxes
        yield ['<?php', '<?php return ' . RectorConfig::class . '::configure()->withPhpPolyfill();', []];
    }
}
