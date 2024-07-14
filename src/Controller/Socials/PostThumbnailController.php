<?php

declare(strict_types=1);

namespace App\Controller\Socials;

use App\Entity\Post;
use App\Enum\FontFile;
use App\Repository\PostRepository;
use App\Thumbnail\ThumbnailGenerator;
use Illuminate\Routing\Controller;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class PostThumbnailController extends Controller
{
    public function __construct(
        private readonly Imagine $imagine,
        private readonly PostRepository $postRepository,
        private readonly ThumbnailGenerator $thumbnailGenerator,
    ) {
    }

    public function __invoke(string $title): BinaryFileResponse
    {
        $imageFilePath = $this->thumbnailGenerator->resolveImageFilePath($title);

        // on the fly
        if (! file_exists($imageFilePath)) {
            $this->createImage($title, $imageFilePath);
        }

        return response()->file($imageFilePath);
    }

    private function createImage(string $title, string $imageFilePath): void
    {
        $box = new Box(2040, 1117);
        $image = $this->imagine->create($box);
        $drawer = $image->draw();

        $blackFont = $this->thumbnailGenerator->createFont(FontFile::SOURCE_SANS_BOLD, '24292e', 100);
        $drawer->text($title, $blackFont, new Point(130, 340), 0, 1800);

        $greenFont = $this->thumbnailGenerator->createFont(FontFile::INTER, '59a35e', 40);

        $post = $this->postRepository->findByTitle($title);

        if ($post instanceof Post && $post->getAuthor() === 'samsonasik') {
            $authorName = 'Abdul Malik Ikhsan';
            $authorPicture = __DIR__ . '/../../public/assets/images/samsonasik_circle.jpg';
        } else {
            $authorName = 'Tomas Votruba';
            $authorPicture = __DIR__ . '/../../public/assets/images/tomas_votruba_circle.jpg';
        }

        $drawer->text("Written by \n" . $authorName, $greenFont, new Point(130, 870), 0, 550);

        // add author face
        $faceImage = $this->imagine->open($authorPicture);
        $faceImage->resize(new Box(200, 200));

        $image->paste($faceImage, new Point(1700, 800));

        $this->thumbnailGenerator->addRectorLogo($image);

        $image->save($imageFilePath);
    }
}
