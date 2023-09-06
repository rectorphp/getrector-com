<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Controller;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\Http\Controllers\ProcessDemoFormController;
use Rector\Website\Tests\AbstractTestCase;

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
            'rector_config' => $configData,
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
            'rector_config' => 'The rector config field is required.',
        ]];

        // missing php open tag
        yield ['failed', 'services:', [
            'php_contents' => 'PHP code is invalid: Missing PHP opening tag',
            'rector_config' => 'PHP code is invalid: Missing PHP opening tag',
        ]];

        // Invalid PHP syntax (missing semicolon)
        yield ['<?php print $x', '', [
            'php_contents' => 'PHP code is invalid: Syntax error, unexpected EOF on line 1',
            'rector_config' => 'The rector config field is required.',
        ]];

        // valid PHP syntaxes
        yield ['<?php', '<?php', []];
    }
}
