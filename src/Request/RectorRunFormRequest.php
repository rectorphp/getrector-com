<?php

declare(strict_types=1);

namespace App\Request;

use App\Validation\Rules\FuncCallRule;
use App\Validation\Rules\HasRectorRule;
use App\Validation\Rules\IncludeRule;
use App\Validation\Rules\ShellExecRule;
use App\Validation\Rules\ShortPhpContentsRule;
use App\Validation\Rules\ValidPhpSyntaxRule;
use Illuminate\Foundation\Http\FormRequest;

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
                $includeRule,
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
