<?php

declare(strict_types=1);

namespace App\Repository;

use App\Ast\Entity\AstRun;

final class AstRunRepository
{
    public function findByHash(string $hash): AstRun
    {
        return AstRun::where('hash', $hash)->firstOrFail();
    }

    public function save(AstRun $astRun): void
    {
        $astRun->save();
    }
}
