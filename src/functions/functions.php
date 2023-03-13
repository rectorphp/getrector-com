<?php

declare(strict_types=1);

use Illuminate\Http\RedirectResponse;

/**
 * @param array<string, mixed> $parameters
 */
function to_action(string $action, array $parameters = []): RedirectResponse
{
    return redirect()->action($action, $parameters);
}
