<?php

namespace Tug\SeoBundle\Renderer;

use Tug\SeoBundle\Model\ModelInterface;

interface RendererInterface
{
    public function render(ModelInterface $model): string;

    public function getModel(): string;
}
