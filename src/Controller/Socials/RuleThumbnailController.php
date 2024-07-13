<?php

declare(strict_types=1);

namespace App\Controller\Socials;

use App\Enum\FontFile;
use App\Exception\ShouldNotHappenException;
use App\FileSystem\RectorFinder;
use App\RuleFilter\ValueObject\RuleMetadata;
use App\Thumbnail\Enum\Color;
use App\Thumbnail\ThumbnailGenerator;
use Illuminate\Routing\Controller;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class RuleThumbnailController extends Controller
{
    public function __construct(
        private readonly Imagine $imagine,
        private readonly RectorFinder $rectorFinder,
        private readonly ThumbnailGenerator $thumbnailGenerator
    ) {
    }

    public function __invoke(string $ruleSlug): BinaryFileResponse
    {
        $rulesMetadata = $this->matchRuleMetadata($ruleSlug);
        if (! $rulesMetadata instanceof RuleMetadata) {
            throw new ShouldNotHappenException();
        }

        $adjustedFontSize = $this->adjustFontSize($rulesMetadata->getRuleShortClass(), 2040);
        $imageFilePath = $this->thumbnailGenerator->resolveImageFilePath($rulesMetadata->getRectorClass());

        // on the fly
        if (! file_exists($imageFilePath) || app('env') === 'dev') {
            $this->createImage($rulesMetadata, $imageFilePath, $adjustedFontSize);
        }

        return response()->file($imageFilePath);
    }

    private function createImage(RuleMetadata $ruleMetadata, string $imageFilePath, int $fontSize): void
    {
        $box = new Box(2040, 1117);
        $image = $this->imagine->create($box);
        $drawer = $image->draw();

        $blackFont = $this->thumbnailGenerator->createFont(FontFile::SOURCE_SANS_BOLD, Color::GREY, $fontSize);
        $drawer->text($ruleMetadata->getRuleShortClass(), $blackFont, new Point(130, 460), 0, 1800);

        $blackFont = $this->thumbnailGenerator->createFont(FontFile::SOURCE_SANS, Color::GREY, 40);
        $drawer->text($ruleMetadata->getDescription(), $blackFont, new Point(130, 660), 0, 1800);

        $this->thumbnailGenerator->addRectorLogo($image);

        $image->save($imageFilePath);
    }

    private function matchRuleMetadata(string $ruleSlug): ?RuleMetadata
    {
        foreach ($this->rectorFinder->findCore() as $ruleMetadata) {
            if ($ruleMetadata->getSlug() === $ruleSlug) {
                return $ruleMetadata;
            }
        }

        return null;
    }

    private function adjustFontSize(string $text, int $maxWidth): int
    {
        $characterCount = strlen($text);
        $averageCharacterWidth = .8; // Estimated average width of a character in pixels

        // Calculate the maximum font size
        $fontSize = $maxWidth / ($characterCount * $averageCharacterWidth);

        return (int) floor($fontSize);
    }
}
