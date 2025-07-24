<?php

namespace Tug\SeoBundle\Registry;

interface ContentInterface
{
    public function setContents(array $contents);

    public function getContents(): array;

    public function getContent(string $blockName): ?array;
}
