<?php

namespace Tug\SeoBundle\Registry;

use Tug\SeoBundle\Model\ModelInterface;
use Tug\SeoBundle\Renderer\RendererInterface as Renderer;

interface RendererInterface
{
    public function set(Renderer $renderer);

    public function render(ModelInterface $model): string;
}
