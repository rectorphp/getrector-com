<?php

declare(strict_types=1);

namespace Tests\Support;

use Codeception\Actor;
use Tests\Support\_generated\AcceptanceTesterActions;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 */
final class AcceptanceTester extends Actor
{
    use AcceptanceTesterActions;

    /**
     * Define custom actions here
     */
}
