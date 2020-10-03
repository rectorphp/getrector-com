<?php

declare(strict_types=1);

namespace Rector\Website\Demo\ValueObject;

final class Option
{
    /**
     * @var string
     */
    public const DEMO_LINKS = 'demo_links';

    /**
     * @var string
     */
    public const FORBIDDEN_FUNCTIONS = 'forbidden_functions';

    /**
     * @var string
     */
    public const HOST_DEMO_DIR = 'host_demo_dir';

    /**
     * @var string
     */
    public const LOCAL_DEMO_DIR = 'local_demo_dir';

    /**
     * @var string
     */
    public const RECTOR_DEMO_DOCKER_IMAGE = 'rector_demo_docker_image';

    /**
     * @var string
     */
    public const DEMO_EXECUTABLE_PATH = 'demo_executable_path';

    /**
     * @var string
     */
    public const SITE_URL = 'site_url';
}
