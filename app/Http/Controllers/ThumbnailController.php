<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\FontInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Rector\Website\Enum\FontFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webmozart\Assert\Assert;

final class ThumbnailController extends Controller
{
    /**
     * @var string
     */
    private const THUMBNAIL_DIRECTORY = __DIR__ . '/../../../storage/thumbnail/';

    public function __construct(
        private readonly Imagine $imagine
    ) {
    }

    public function __invoke(string $title): BinaryFileResponse
    {
        $imageFilePath = $this->resolveImageFilePath($title);

        // on the fly
        if (! file_exists($imageFilePath)) {
            $this->createImage($title, $imageFilePath);
        }

        return response()->file($imageFilePath);
    }

    /**
     * @param FontFile::* $fontFamilyFile
     */
    private function createFont(string $fontFamilyFile, string $hexColor, int $fontSize): FontInterface
    {
        Assert::fileExists($fontFamilyFile);

        $rgb = new RGB();
        $color = $rgb->color($hexColor);

        return $this->imagine->font($fontFamilyFile, $fontSize, $color);
    }

    private function createImage(string $title, string $imageFilePath): void
    {
        $box = new Box(2040, 1117);
        $image = $this->imagine->create($box);
        $drawer = $image->draw();

        $blackFont = $this->createFont(FontFile::SOURCE_SANS, '000000', 100);
        $drawer->text($title, $blackFont, new Point(130, 340), 0, 1800);

        $greenFont = $this->createFont(FontFile::INTER, '1a8917', 40);
        $drawer->text('Written by Tomas Votruba', $greenFont, new Point(130, 870), 0, 400);

        // add my face :)
        $faceImage = $this->imagine->open(__DIR__ . '/../../../public/assets/images/tomas_votruba_circle.jpg');
        $faceImage->resize(new Box(200, 200));

        $image->paste($faceImage, new Point(1700, 800));
        $image->save($imageFilePath);
    }

    private function resolveImageFilePath(string $title): string
    {
        // ensure directory exists
        if (! is_dir(self::THUMBNAIL_DIRECTORY)) {
            FileSystem::createDir(self::THUMBNAIL_DIRECTORY);
        }

        return self::THUMBNAIL_DIRECTORY . '/' . Strings::webalize($title) . '.png';
    }
}
