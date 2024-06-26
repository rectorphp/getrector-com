<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Enum\FontFile;
use App\Repository\PostRepository;
use Illuminate\Routing\Controller;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\FontInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Webmozart\Assert\Assert;

final class ThumbnailController extends Controller
{
    /**
     * @var string
     */
    private const THUMBNAIL_DIRECTORY = __DIR__ . '/../../../storage/thumbnail/';

    public function __construct(
        private readonly Imagine $imagine,
        private readonly PostRepository $postRepository,
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

        $blackFont = $this->createFont(FontFile::SOURCE_SANS, '24292e', 100);
        $drawer->text($title, $blackFont, new Point(130, 340), 0, 1800);

        $greenFont = $this->createFont(FontFile::INTER, '59a35e', 40);

        $post = $this->postRepository->findByTitle($title);

        if ($post instanceof Post && $post->getAuthor() === 'samsonasik') {
            $authorName = 'Abdul Malik Ikhsan';
            $authorPicture = __DIR__ . '/../../../public/assets/images/samsonasik_circle.jpg';
        } else {
            $authorName = 'Tomas Votruba';
            $authorPicture = __DIR__ . '/../../../public/assets/images/tomas_votruba_circle.jpg';
        }

        $drawer->text("Written by \n" . $authorName, $greenFont, new Point(130, 870), 0, 550);

        // add author face
        $faceImage = $this->imagine->open($authorPicture);
        $faceImage->resize(new Box(200, 200));

        $image->paste($faceImage, new Point(1700, 800));

        $rectorLogoImage = $this->imagine->open(__DIR__ . '/../../../public/assets/images/logo/rector.png');
        $rectorLogoImage->resize(new Box(716 * .75, 175 * .75));

        $image->paste($rectorLogoImage, new Point(1400, 100));

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
