<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\Demo\ProcessDemoFormController;
use App\Tests\AbstractTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;

final class DemoControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    /**
     * @param array<string, string[]> $invalidKeysWithMessages
     */
    #[DataProvider('provideTestFormSubmitData')]
    public function testFormSubmit(string $contentData, string $configData, array $invalidKeysWithMessages): void
    {
        $postUrl = action(ProcessDemoFormController::class);

        $testResponse = $this->post($postUrl, [
            'php_contents' => $contentData,
            'runnable_contents' => $configData,
        ]);

        $this->assertTrue($testResponse->isRedirect());

        if ($invalidKeysWithMessages !== []) {
            $this->assertFalse($testResponse->isSuccessful());

            // assert what inputs are invalid => error message
            $testResponse->assertInvalid($invalidKeysWithMessages);
        }
    }

    public static function provideTestFormSubmitData(): Iterator
    {
        // Send empty form
        yield ['', '', [
            'php_contents' => 'The php contents field is required.',
            'runnable_contents' => 'The runnable contents field is required.',
        ]];

        // missing php open tag
        yield ['failed', 'services:', [
            'php_contents' => 'PHP code is invalid: Missing PHP opening tag',
            'runnable_contents' => 'PHP code is invalid: Missing PHP opening tag',
        ]];

        // Invalid PHP syntax (missing semicolon in code box)
        yield ['<?php print $x', '', [
            'php_contents' => 'PHP code is invalid: Syntax error, unexpected EOF on line 1',
            'runnable_contents' => 'The runnable contents field is required.',
        ]];

        // Invalid PHP syntax (missing semicolon in config box)
        yield ['<?php print $x; ?>', '<?php return static function() {}', [
            'runnable_contents' => "PHP code is invalid: Syntax error, unexpected EOF, expecting ';' on line 1",
        ]];

        // Add dangerous exec() func call
        yield ['<?php echo "test"; ?>', '<?php exec("dangerous command"); ?>', [
            'runnable_contents' => 'PHP config should not include func call',
        ]];

        // Add dangerous `` execution operator
        yield ['<?php echo "test"; ?>', '<?php `dangerous command`; ?>', [
            'runnable_contents' => 'PHP config should not include execution operator',
        ]];

        // include file is dangerous
        yield ['<?php echo "test"; ?>', '<?php include "index.php"; ?>', [
            'runnable_contents' => 'PHP config should not include include/require usage',
        ]];

        // Add no rule in config
        yield ['<?php echo "test"; ?>', '<?php $rectorConfig->removeUnusedImports(); ?>', [
            'runnable_contents' => 'PHP config should include at least 1 rector rule',
        ]];

        // valid PHP syntaxes
        yield ['<?php', '<?php', []];
    }
}
