<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DemoFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'php_contents' => ['required', 'string'],
            'rector_config' => ['required', 'string'],
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
