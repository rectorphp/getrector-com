<?php

declare(strict_types=1);

namespace Rector\Website\Tests\Controller;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Website\Enum\RouteName;
use Rector\Website\Tests\AbstractTestCase;

final class DemoControllerTest extends AbstractTestCase
{
    /**
     * @param array<string, string[]> $invalidKeysWithMessages
     */
    #[DataProvider('provideTestFormSubmitData')]
    public function testFormSubmit(string $contentData, string $configData, array $invalidKeysWithMessages): void
    {
        $postUrl = route(RouteName::PROCESS_DEMO_FORM);

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

        // Invalid PHP syntax
        yield ['failed', 'services:', [
            'php_contents' => 'Provide a valid PHP code',
            'rector_config' => 'Provide a valid PHP code',
        ]];

        // valid PHP syntaxes
        yield ['<?php', '<?php', []];
    }
}
