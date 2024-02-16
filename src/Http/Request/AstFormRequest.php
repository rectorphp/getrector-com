<?php

declare(strict_types=1);

namespace Rector\Website\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Rector\Website\Rules\ShortPhpContentsRule;
use Rector\Website\Rules\ValidPhpSyntaxRule;

final class AstFormRequest extends FormRequest
{
    /**
     * @var string
     */
    private const KEY_PHP_CONTENTS = 'php_contents';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string,mixed[]>
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
            self::KEY_PHP_CONTENTS => ['required', 'string', $shortPhpContentsRule, $validPhpSyntaxRule],
        ];
    }

    public function getPhpContents(): string
    {
        return $this->string(self::KEY_PHP_CONTENTS)
            ->value();
    }
}
