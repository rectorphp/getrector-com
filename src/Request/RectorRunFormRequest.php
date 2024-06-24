<?php

declare(strict_types=1);

namespace Rector\Website\Request;

use Illuminate\Foundation\Http\FormRequest;
use Rector\Website\Validation\Rules\FuncCallRule;
use Rector\Website\Validation\Rules\HasRectorRule;
use Rector\Website\Validation\Rules\IncludeRule;
use Rector\Website\Validation\Rules\ShellExecRule;
use Rector\Website\Validation\Rules\ShortPhpContentsRule;
use Rector\Website\Validation\Rules\ValidPhpSyntaxRule;

final class RectorRunFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var ShortPhpContentsRule $shortPhpContentsRule */
        $shortPhpContentsRule = app()
            ->make(ShortPhpContentsRule::class);

        /** @var ValidPhpSyntaxRule $validPhpSyntaxRule */
        $validPhpSyntaxRule = app()
            ->make(ValidPhpSyntaxRule::class);

        /** @var FuncCallRule $funcCallRule */
        $funcCallRule = app()
            ->make(FuncCallRule::class);

        $shellExecRule = app()
            ->make(ShellExecRule::class);

        /** @var HasRectorRule $hasRectorRule */
        $hasRectorRule = app()
            ->make(HasRectorRule::class);

        /** @var IncludeRule $includeRule */
        $includeRule = app()
            ->make(IncludeRule::class);

        return [
            'php_contents' => ['required', 'string', $shortPhpContentsRule, $validPhpSyntaxRule],
            'runnable_contents' => [
                'required',
                'string',
                $validPhpSyntaxRule,
                $funcCallRule,
                $hasRectorRule,
                $shellExecRule,
                $includeRule
            ],
        ];
    }

    public function getPhpContents(): string
    {
        return $this->string('php_contents')
            ->value();
    }

    public function getRunnableContents(): string
    {
        return $this->string('runnable_contents')
            ->value();
    }
}
