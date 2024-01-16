<?php

declare(strict_types=1);

namespace Rector\Website\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PhpContentAndRectorConfig
{
    /**
     * @param \Closure(Request):Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $phpContents = $request->request->get('php_contents');
        $rectorConfig = $request->request->get('rector_config');

        $session = $request->session();

        if (is_string($phpContents) && is_string($rectorConfig)) {
            $session->flashInput([
                'php_contents' => $phpContents,
                'rector_config' => $rectorConfig,
            ]);
        }

        return $next($request);
    }
}