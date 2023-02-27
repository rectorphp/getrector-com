<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ShortPhpContentsRule;
use App\Rules\ValidPhpSyntaxRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DemoFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<ValidationRule|string>>
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
