<?php

declare(strict_types=1);

<<<<<<<< HEAD:src/Enum/RouteName.php
namespace Rector\Website\Enum;
========
namespace Rector\Website\ValueObject;
>>>>>>>> 044a866 (shorter name):src/ValueObject/RouteName.php

final class RouteName
{
    /**
     * @var string
     */
    public const ABOUT = 'about';

    /**
     * @var string
     */
    public const HOMEPAGE = 'homepage';

    /**
     * @var string
     */
    public const RSS = 'rss';

    /**
     * @var string
     */
    public const BLOG = 'blog';

    /**
     * @var string
     */
    public const POST = 'post';

    /**
     * @var string
     */
    public const CONTACT = 'contact';

    /**
     * @var string
     */
    public const DEMO_DETAIL = 'demo_detail';

    /**
     * @var string
     */
    public const DEMO = 'demo';

    /**
     * @var string
     */
    public const BOOK = 'book';

    /**
     * @var string
     */
    public const HIRE_TEAM = 'hire_team';

    /**
     * @api
     * @var string
     */
    public const DOCUMENTATION = 'documentation';
}
