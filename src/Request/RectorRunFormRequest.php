<?php

declare(strict_types=1);

namespace App\Request;

use App\Validation\Rules\ForbiddenFuncCallRule;
use App\Validation\Rules\HasRectorRule;
use App\Validation\Rules\ShellExecRule;
use App\Validation\Rules\ShortPhpContentsRule;
use App\Validation\Rules\ValidAndSafePhpSyntaxRule;
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
        $shortPhpContentsRule = $this->make(ShortPhpContentsRule::class);
        $validAndSafePhpSyntaxRule = $this->make(ValidAndSafePhpSyntaxRule::class);
        $forbiddenFuncCallRule = $this->make(ForbiddenFuncCallRule::class);
        $shellExecRule = $this->make(ShellExecRule::class);

        $hasRectorRule = $this->make(HasRectorRule::class);

        return [
            'php_contents' => ['bail', 'required', 'string', $shortPhpContentsRule, $validAndSafePhpSyntaxRule],
            'runnable_contents' => [
                // "bail" = stop after first error, as next does not make sense
                'bail',
                'required',
                'string',
                $validAndSafePhpSyntaxRule,
                $shellExecRule,
                $forbiddenFuncCallRule,
                $hasRectorRule,
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

    /**
     * @template TService as object
     *
     * @param class-string<TService> $type
     * @return TService
     */
    private function make(string $type): mixed
    {
        $app = app();
        return $app->make($type);
    }
}
