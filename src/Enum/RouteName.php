<?php

declare(strict_types=1);

namespace Rector\Website\Enum;

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
     * @api use in blade route
     * @var string
     */
    public const DOCUMENTATION = 'documentation';

    /**
     * @api use in blade route
     * @var string
     */
    public const PROCESS_DEMO_FORM = 'process_demo_form';

    /**
     * @var string
     */
    public const POST_IMAGE = 'post_image';
}
