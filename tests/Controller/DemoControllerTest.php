<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\Demo\ProcessDemoFormController;
use App\Enum\Request\FormKey;
use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Config\RectorConfig;
use Webmozart\Assert\Assert;

final class DemoControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * @param array<FormKey::*, string[]> $expectedInvalidKeysWithMessages
     */
    #[DataProvider('provideTestFormSubmitData')]
    public function testInvalidRequest(
        string $phpContentsData,
        string $runnableContentsData,
        array $expectedInvalidKeysWithMessages
    ): void {
        Assert::notEmpty($expectedInvalidKeysWithMessages);

        $postUrl = action(ProcessDemoFormController::class);

        $testResponse = $this->post($postUrl, [
            FormKey::PHP_CONTENTS => $phpContentsData,
            FormKey::RUNNABLE_CONTENTS => $runnableContentsData,
        ]);

        $this->assertTrue($testResponse->isRedirect());

        // assert what inputs are invalid => error message
        $testResponse->assertInvalid($expectedInvalidKeysWithMessages);
    }

    public function testValidRequest(): void
    {
        $postUrl = action(ProcessDemoFormController::class);

        $testResponse = $this->post($postUrl, [
            FormKey::PHP_CONTENTS => '<?php',
            FormKey::RUNNABLE_CONTENTS => '<?php return ' . RectorConfig::class . '::configure()->withPhpPolyfill();',
        ]);

        $this->assertTrue($testResponse->isRedirect());

        $this->assertFalse($testResponse->isClientError());
        $this->assertFalse($testResponse->isServerError());
    }

    public function testIncludeNonDangerousFuncCallRequest(): void
    {
        $postUrl = action(ProcessDemoFormController::class);

        $testResponse = $this->post($postUrl, [
            FormKey::PHP_CONTENTS => '<?php',
            FormKey::RUNNABLE_CONTENTS => '<?php var_dump("test");  return ' . RectorConfig::class . '::configure()->withPhpPolyfill();',
        ]);

        $this->assertTrue($testResponse->isRedirect());

        $this->assertFalse($testResponse->isClientError());
        $this->assertFalse($testResponse->isServerError());
    }

    public static function provideTestFormSubmitData(): Iterator
    {
        // Send empty form
        yield ['', '', [
            FormKey::PHP_CONTENTS => 'The php contents field is required.',
            FormKey::RUNNABLE_CONTENTS => 'The runnable contents field is required.',
        ]];

        // missing php open tag
        yield ['failed', 'services:', [
            FormKey::PHP_CONTENTS => 'PHP code is invalid: Missing PHP opening tag',
            FormKey::RUNNABLE_CONTENTS => 'PHP code is invalid: Missing PHP opening tag',
        ]];

        // Invalid PHP syntax (missing semicolon in code box)
        yield ['<?php print $x', '', [
            FormKey::PHP_CONTENTS => 'PHP code is invalid: Syntax error, unexpected EOF on line 1',
            FormKey::RUNNABLE_CONTENTS => 'The runnable contents field is required.',
        ]];

        // Invalid PHP syntax (missing semicolon in config box)
        yield ['<?php print $x; ?>', '<?php return static function() {}', [
            FormKey::RUNNABLE_CONTENTS => "PHP code is invalid: Syntax error, unexpected EOF, expecting ';' on line 1",
        ]];

        // Add dangerous exec() func call
        yield ['<?php echo "test"; ?>', '<?php exec("dangerous command"); ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should not include side effect func call',
        ]];

        // Add dangerous exec() func call
        yield ['<?php echo "test"; ?>', '<?php \exec("dangerous command"); ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should not include side effect func call',
        ]];

        yield ['<?php echo "test"; ?>', '<?php `dangerous command`; ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should not include execution operator',
        ]];

        yield ['<?php echo "test"; ?>', '<?php include "index.php"; ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP code cannot contain any "include"/"require" calls',
        ]];

        // Add no rule in config
        yield ['<?php echo "test"; ?>', '<?php return ' . RectorConfig::class . '::configure()->withImportNames(); ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should include at least 1 rector rule',
        ]];

        // Typo in config
        yield ['<?php echo "test typo"; ?>', '<?php return ' . RectorConfig::class . '::configure()->some(); ?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should have valid method name, you may have typo',
        ]];

        // not callable
        yield ['<?php echo "test typo"; ?>', '<?php return (new DateTimeImmutable("2000-01-01"))->add(new DateInterval("P10D")); ?>', [
            FormKey::RUNNABLE_CONTENTS => 'Expected config should return callable RectorConfig instance',
        ]];

        // provide not exists paths
        yield ['<?php echo "test typo"; ?>', '<?php return ' . RectorConfig::class . '::configure()->withPaths(["non_exists_paths"]);?>', [
            FormKey::RUNNABLE_CONTENTS => 'PHP config should have valid paths',
        ]];
    }
}
