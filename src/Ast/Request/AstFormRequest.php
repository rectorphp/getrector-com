<?php

declare(strict_types=1);

namespace App\Ast\Request;

use App\Validation\Rules\ShortPhpContentsRule;
use App\Validation\Rules\ValidAndSafePhpSyntaxRule;
use Illuminate\Foundation\Http\FormRequest;

final class AstFormRequest extends FormRequest
{
    private const string KEY_PHP_CONTENTS = 'php_contents';

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

        /** @var ValidAndSafePhpSyntaxRule $validAndSafePhpSyntaxRule */
        $validAndSafePhpSyntaxRule = app()
            ->make(ValidAndSafePhpSyntaxRule::class);

        return [
            self::KEY_PHP_CONTENTS => ['required', 'string', $shortPhpContentsRule, $validAndSafePhpSyntaxRule],
        ];
    }

    public function getPhpContents(): string
    {
        return $this->string(self::KEY_PHP_CONTENTS)
            ->value();
    }
}
