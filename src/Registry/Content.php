<?php 

namespace Tug\SeoBundle\Registry;

class Content implements ContentInterface
{
    private array $contents = [];

    public function setContents(array $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    public function getContent(string $blockName): ?array
    {
        return $this->contents[$blockName] ?? null;
    }
}
