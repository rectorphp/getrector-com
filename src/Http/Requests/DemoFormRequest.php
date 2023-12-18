<?php

declare(strict_types=1);

namespace Rector\Website\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Rector\Website\Rules\ShortPhpContentsRule;
use Rector\Website\Rules\ValidPhpSyntaxRule;

final class DemoFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{php_contents: ShortPhpContentsRule[]|ValidPhpSyntaxRule[]|string[], rector_config: ValidPhpSyntaxRule[]|string[]}
     */
    public function rules(): array
    {
        /** @var ShortPhpContentsRule $shortPhpContentsRule */
        $shortPhpContentsRule = app()
            ->make(ShortPhpContentsRule::class);

        /** @var ValidPhpSyntaxRule $validPhpSyntaxRule */
        $validPhpSyntaxRule = app()
            ->make(ValidPhpSyntaxRule::class);

        return [
            'php_contents' => ['required', 'string', $shortPhpContentsRule, $validPhpSyntaxRule],
            'rector_config' => ['required', 'string', $validPhpSyntaxRule],
        ];
    }

    public function getPhpContents(): string
    {
        return $this->string('php_contents')
            ->value();
    }

    public function getRectorConfig(): string
    {
        return $this->string('rector_config')
            ->value();
    }
}
