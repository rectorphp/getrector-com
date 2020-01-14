<?php declare (strict_types=1);

namespace Rector\Website\Form;

final class RectorRunFormData
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $setName;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getSetName(): ?string
    {
        return $this->setName;
    }

    public function setSetName(string $setName): void
    {
        $this->setName = $setName;
    }
}
