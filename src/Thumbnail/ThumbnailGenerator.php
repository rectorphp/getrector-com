<?php

declare(strict_types=1);

namespace App\Thumbnail;

use Imagine\Gd\Imagine;
use Imagine\Image\AbstractFont;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Webmozart\Assert\Assert;

final readonly class ThumbnailGenerator
{
    /**
     * @var string
     */
    private const THUMBNAIL_DIRECTORY = __DIR__ . '/../../../storage/thumbnail/';

    public function __construct(
        private Imagine $imagine
    ) {
    }

    public function createFont(string $fontFilePath, string $color, int $fontSize): AbstractFont
    {
        Assert::fileExists($fontFilePath);

        $rgb = new RGB();
        $color = $rgb->color($color);

        return $this->imagine->font($fontFilePath, $fontSize, $color);
    }

    public function addRectorLogo(ImageInterface $image): void
    {
        $rectorLogoImage = $this->imagine->open(__DIR__ . '/../../public/assets/images/logo/rector.png');
        $rectorLogoImage->resize(new Box(716 * .75, 175 * .75));

        $image->paste($rectorLogoImage, new Point(1400, 100));
    }

    public function resolveImageFilePath(string $title): string
    {
        // ensure directory exists
        if (! is_dir(self::THUMBNAIL_DIRECTORY)) {
            FileSystem::createDir(self::THUMBNAIL_DIRECTORY);
        }

        return self::THUMBNAIL_DIRECTORY . '/' . Strings::webalize($title) . '.png';
    }
}
